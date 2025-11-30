<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    /**
     * Mapping hari yang dipakai di dropdown.
     * Key disimpan di DB (lowercase), value untuk tampilan.
     */
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
     * Dokter melihat semua jadwal praktik miliknya sendiri.
     */
    public function index()
    {
        $doctorId = $this->getLoggedInDoctorId();

        $schedules = Schedule::where('doctor_id', $doctorId)
            ->orderByRaw("FIELD(day_of_week, 'senin','selasa','rabu','kamis','jumat','sabtu','minggu')")
            ->orderBy('start_time')
            ->get();

        return view('doctor.schedules.index', [
            'schedules' => $schedules,
            'days'      => $this->days,
        ]);
    }

    /**
     * Form tambah jadwal baru.
     */
    public function create()
    {
        $days = $this->days;

        return view('doctor.schedules.create', compact('days'));
    }

    /**
     * Simpan jadwal baru untuk dokter yang sedang login.
     */
    public function store(Request $request)
    {
        $doctorId = $this->getLoggedInDoctorId();

        // Normalisasi input: hari lowercase, waktu ke format H:i
        $request->merge([
            'day_of_week' => strtolower($request->day_of_week),
            'start_time'  => $request->start_time
                ? Carbon::parse($request->start_time)->format('H:i')
                : null,
            'end_time'    => $request->end_time
                ? Carbon::parse($request->end_time)->format('H:i')
                : null,
        ]);

        try {
            // Validasi field hari & jam (tanpa doctor_id, karena kita pakai dokter login)
            $validated = $this->validateScheduleRequest($request);

            // Tambahkan doctor_id dokter yang login
            $validated['doctor_id'] = $doctorId;

            // Cek bentrok jadwal (overlap) di hari yang sama
            if ($this->isOverlapping($doctorId, $validated['day_of_week'], $validated['start_time'], $validated['end_time'])) {
                return back()->withInput()->withErrors([
                    'start_time' => 'Jadwal bertabrakan dengan jadwal lain pada hari ' . $this->days[$validated['day_of_week']] . '.',
                ]);
            }

            Schedule::create($validated);

            return redirect()
                ->route('doctor.schedules.index')
                ->with('success', 'Jadwal praktik berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error("Schedule Store Error (Doctor): " . $e->getMessage(), ['exception' => $e]);

            $msg = $e->getMessage() === 'Jam selesai harus lebih besar dari jam mulai.'
                ? $e->getMessage()
                : 'Gagal menyimpan jadwal. Silakan coba lagi atau hubungi admin.';

            return back()->withInput()
                ->with('error', $msg)
                ->withErrors(['general' => $msg]);
        }
    }

    /**
     * Form edit jadwal. Dokter hanya boleh mengedit jadwal miliknya sendiri.
     */
    public function edit(Schedule $schedule)
    {
        $this->authorizeSchedule($schedule);

        $days = $this->days;

        return view('doctor.schedules.edit', compact('schedule', 'days'));
    }

    /**
     * Update jadwal praktik.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $this->authorizeSchedule($schedule);

        $doctorId = $this->getLoggedInDoctorId();

        // Normalisasi input
        $request->merge([
            'day_of_week' => strtolower($request->day_of_week),
            'start_time'  => $request->start_time
                ? Carbon::parse($request->start_time)->format('H:i')
                : null,
            'end_time'    => $request->end_time
                ? Carbon::parse($request->end_time)->format('H:i')
                : null,
        ]);

        try {
            $validated = $this->validateScheduleRequest($request);

            // Tambahkan doctor_id dokter login (jaga-jaga)
            $validated['doctor_id'] = $doctorId;

            // Cek bentrok jadwal, kecuali dengan jadwal yang sedang diedit
            if ($this->isOverlapping(
                $doctorId,
                $validated['day_of_week'],
                $validated['start_time'],
                $validated['end_time'],
                $schedule->id
            )) {
                return back()->withInput()->withErrors([
                    'start_time' => 'Jadwal bertabrakan dengan jadwal lain pada hari ' . $this->days[$validated['day_of_week']] . '.',
                ]);
            }

            $schedule->update($validated);

            return redirect()
                ->route('doctor.schedules.index')
                ->with('success', 'Jadwal praktik berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error("Schedule Update Error (Doctor): " . $e->getMessage(), ['exception' => $e]);

            $msg = $e->getMessage() === 'Jam selesai harus lebih besar dari jam mulai.'
                ? $e->getMessage()
                : 'Gagal memperbarui jadwal. Silakan coba lagi atau hubungi admin.';

            return back()->withInput()
                ->with('error', $msg)
                ->withErrors(['general' => $msg]);
        }
    }

    /**
     * Hapus jadwal praktik.
     */
    public function destroy(Schedule $schedule)
    {
        $this->authorizeSchedule($schedule);

        $schedule->delete();

        return redirect()
            ->route('doctor.schedules.index')
            ->with('success', 'Jadwal praktik berhasil dihapus.');
    }

    /**
     * Validasi field jadwal (tanpa doctor_id karena di-set dari dokter login).
     */
    protected function validateScheduleRequest(Request $request): array
    {
        return $request->validate([
            'day_of_week' => ['required', Rule::in(array_keys($this->days))],
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i', 'after:start_time'],
        ]);
    }

    /**
     * Cek apakah jadwal bentrok (overlap) dengan jadwal lain di hari yang sama.
     */
    protected function isOverlapping(
        int $doctorId,
        string $dayOfWeek,
        string $startTime,
        string $endTime,
        ?int $ignoreId = null
    ): bool {
        $query = Schedule::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->where(function ($q) use ($startTime, $endTime) {
            $q->whereBetween('start_time', [$startTime, $endTime])
              ->orWhereBetween('end_time', [$startTime, $endTime])
              ->orWhere(function ($q2) use ($startTime, $endTime) {
                  $q2->where('start_time', '<=', $startTime)
                     ->where('end_time', '>=', $endTime);
              });
        })->exists();
    }

    /**
     * Ambil ID dokter dari user yang login.
     * Sesuaikan relasi ini dengan project-mu:
     * misal: User -> hasOne(DoctorDetail, 'user_id')
     */
    protected function getLoggedInDoctorId(): int
    {
        $user = Auth::user();

        // SESUAIKAN nama relasi ini dengan yang ada di model User-mu
        // misal: $user->doctorDetail atau $user->doctor
        $doctorDetail = $user->doctorDetail; 

        if (! $doctorDetail) {
            abort(403, 'Akun ini tidak memiliki data dokter.');
        }

        return $doctorDetail->id;
    }

    /**
     * Pastikan jadwal yang diakses memang milik dokter yang login.
     */
    protected function authorizeSchedule(Schedule $schedule): void
    {
        $doctorId = $this->getLoggedInDoctorId();

        if ($schedule->doctor_id !== $doctorId) {
            abort(403, 'Anda tidak berhak mengelola jadwal ini.');
        }
    }
}
