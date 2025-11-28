<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\DoctorDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    // key = value yang disimpan di DB, value = label yang tampil di form
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
     * LIST jadwal dokter
     */
    public function index()
    {
        $schedules = Schedule::with(['doctor.user', 'doctor.poli'])
            ->orderBy('doctor_id')
            ->orderByRaw("FIELD(day_of_week, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->orderBy('start_time')
            ->paginate(15);

        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * FORM tambah jadwal
     */
    public function create()
    {
        $doctors = DoctorDetail::with(['user', 'poli'])->get();
        $days    = $this->days;

        return view('admin.schedules.create', compact('doctors', 'days'));
    }

    /**
     * SIMPAN jadwal baru
     */
    public function store(Request $request)
    {
        $validated = $this->validateScheduleRequest($request);

        // Cegah duplikasi jadwal dokter di hari yang sama
        $isDuplicate = Schedule::where('doctor_id', $validated['doctor_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->exists();

        if ($isDuplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'day_of_week' => 'Dokter ini sudah memiliki jadwal pada hari ' . $this->days[$validated['day_of_week']] . '.',
                ]);
        }

        Schedule::create($validated);

        // Tambahkan pesan sukses setelah data berhasil disimpan
        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal Dokter berhasil ditambahkan.');
    }

    /**
     * FORM edit jadwal
     */
    public function edit(Schedule $schedule)
    {
        $schedule->load('doctor.user', 'doctor.poli');
        $doctors = DoctorDetail::with(['user', 'poli'])->get();
        $days    = $this->days;

        return view('admin.schedules.edit', compact('schedule', 'doctors', 'days'));
    }

    /**
     * UPDATE jadwal
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $this->validateScheduleRequest($request);

        $isDuplicate = Schedule::where('doctor_id', $validated['doctor_id'])
            ->where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($isDuplicate) {
            return back()
                ->withInput()
                ->withErrors([
                    'day_of_week' => 'Dokter ini sudah memiliki jadwal lain pada hari ' . $this->days[$validated['day_of_week']] . '.',
                ]);
        }

        $schedule->update($validated);

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal Dokter berhasil diperbarui.');
    }

    /**
     * HAPUS jadwal
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }

    /**
     * Helper VALIDASI request
     */
    protected function validateScheduleRequest(Request $request): array
    {
        return $request->validate([
            'doctor_id' => ['required', 'exists:doctor_details,id'],
            'day_of_week' => ['required', Rule::in(array_keys($this->days))],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
        ]);
    }
}
