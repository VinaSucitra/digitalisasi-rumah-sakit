<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    private array $days = [
        'senin'   => 'Senin',
        'selasa'  => 'Selasa',
        'rabu'    => 'Rabu',
        'kamis'   => 'Kamis',
        'jumat'   => 'Jumat',
        'sabtu'   => 'Sabtu',
        'minggu'  => 'Minggu',
    ];

    /**
     * List My Schedules
     */
    public function index()
    {
        $doctor = auth()->user()->doctorDetail; // relasi user -> doctor_detail

        $schedules = Schedule::where('doctor_id', $doctor->id)
            ->orderByRaw("FIELD(day_of_week, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->orderBy('start_time')
            ->paginate(15);

        return view('doctor.schedules.index', [
            'schedules' => $schedules,
            'days'      => $this->days,
        ]);
    }

    /**
     * Form Create Schedule
     */
    public function create()
    {
        return view('doctor.schedules.create', [
            'days' => $this->days,
        ]);
    }

    /**
     * Store Schedule – durasi selalu 30 menit, tidak boleh tumpang tindih
     */
    public function store(Request $request)
    {
        $doctor = auth()->user()->doctorDetail;

        // normalisasi input
        $request->merge([
            'day_of_week' => strtolower($request->day_of_week),
        ]);

        // validasi dasar
        $validated = $request->validate([
            'day_of_week' => ['required', Rule::in(array_keys($this->days))],
            'start_time'  => ['required', 'date_format:H:i'],
        ]);

        // hitung otomatis end_time = start + 30 menit
        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end   = (clone $start)->addMinutes(30);

        $startStr = $start->format('H:i');
        $endStr   = $end->format('H:i');

        // cek tumpang tindih
        $overlap = Schedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where(function ($q) use ($startStr, $endStr) {
                $q->whereBetween('start_time', [$startStr, $endStr])
                  ->orWhereBetween('end_time', [$startStr, $endStr])
                  ->orWhere(function ($q2) use ($startStr, $endStr) {
                      $q2->where('start_time', '<=', $startStr)
                         ->where('end_time', '>=', $endStr);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'Jadwal bertumpuk dengan jadwal lain pada hari ' .
                                    $this->days[$validated['day_of_week']] . '.',
                ]);
        }

        Schedule::create([
            'doctor_id'  => $doctor->id,
            'day_of_week'=> $validated['day_of_week'],
            'start_time' => $startStr,
            'end_time'   => $endStr,
        ]);

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Jadwal baru berhasil ditambahkan.');
    }

    /**
     * Form Edit
     */
    public function edit(Schedule $schedule)
    {
        $doctor = auth()->user()->doctorDetail;

        // pastikan jadwal ini milik dokter yang login
        abort_if($schedule->doctor_id !== $doctor->id, 403);

        return view('doctor.schedules.edit', [
            'schedule' => $schedule,
            'days'     => $this->days,
        ]);
    }

    /**
     * Update Schedule
     */
    public function update(Request $request, Schedule $schedule)
    {
        $doctor = auth()->user()->doctorDetail;
        abort_if($schedule->doctor_id !== $doctor->id, 403);

        $request->merge([
            'day_of_week' => strtolower($request->day_of_week),
        ]);

        $validated = $request->validate([
            'day_of_week' => ['required', Rule::in(array_keys($this->days))],
            'start_time'  => ['required', 'date_format:H:i'],
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end   = (clone $start)->addMinutes(30);

        $startStr = $start->format('H:i');
        $endStr   = $end->format('H:i');

        // cek tumpang tindih (kecuali jadwal yang sedang di-edit)
        $overlap = Schedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $schedule->id)
            ->where(function ($q) use ($startStr, $endStr) {
                $q->whereBetween('start_time', [$startStr, $endStr])
                  ->orWhereBetween('end_time', [$startStr, $endStr])
                  ->orWhere(function ($q2) use ($startStr, $endStr) {
                      $q2->where('start_time', '<=', $startStr)
                         ->where('end_time', '>=', $endStr);
                  });
            })
            ->exists();

        if ($overlap) {
            return back()
                ->withInput()
                ->withErrors([
                    'start_time' => 'Jadwal bertumpuk dengan jadwal lain pada hari ' .
                                    $this->days[$validated['day_of_week']] . '.',
                ]);
        }

        $schedule->update([
            'day_of_week' => $validated['day_of_week'],
            'start_time'  => $startStr,
            'end_time'    => $endStr,
        ]);

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Hapus jadwal
     */
    public function destroy(Schedule $schedule)
    {
        $doctor = auth()->user()->doctorDetail;
        abort_if($schedule->doctor_id !== $doctor->id, 403);

        $schedule->delete();

        return redirect()->route('doctor.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
