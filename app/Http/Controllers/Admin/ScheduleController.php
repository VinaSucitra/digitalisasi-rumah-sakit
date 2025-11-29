<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\DoctorDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
     * Menampilkan semua jadwal dokter.
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
     * Form untuk tambah jadwal baru.
     */
    public function create()
    {
        $doctors = DoctorDetail::with(['user', 'poli'])->get();
        $days = $this->days;

        return view('admin.schedules.create', compact('doctors', 'days'));
    }

    /**
     * Menyimpan jadwal baru.
     */
    public function store(Request $request)
    {
        // 1. KONSISTENSI INPUT: Gunakan 'day_of_week' yang harusnya sudah diperbaiki di view
        // Pastikan nilai hari lowercase agar sesuai dengan kunci array $this->days
        $request->merge([
            // Konversi format waktu browser (misalnya 02:02 PM) ke H:i (24 jam)
            'start_time' => $request->start_time ? Carbon::parse($request->start_time)->format('H:i') : null,
            'end_time' => $request->end_time ? Carbon::parse($request->end_time)->format('H:i') : null,
            'day_of_week' => strtolower($request->day_of_week), 
        ]);

        try {
            // Validasi request
            $validated = $this->validateScheduleRequest($request);

            // Cek apakah jadwal duplikat
            $isDuplicate = Schedule::where('doctor_id', $validated['doctor_id'])
                ->where('day_of_week', $validated['day_of_week'])
                ->where('start_time', $validated['start_time']) 
                ->exists();

            if ($isDuplicate) {
                return back()->withInput()->withErrors([
                    'day_of_week' => 'Dokter ini sudah memiliki jadwal pada waktu yang sama pada hari ' . $this->days[$validated['day_of_week']] . '.',
                ]);
            }

            // Simpan jadwal ke database
            Schedule::create($validated);

            // Redirect dan beri pesan sukses
            return redirect()->route('admin.schedules.index')
                             ->with('success', 'Jadwal Dokter berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Catch error validasi
            return back()->withInput()->withErrors($e->errors());
        
        } catch (\Exception $e) {
            // Tangani kesalahan penyimpanan (DB error atau Model Hook error)
            Log::error("Schedule Store Error: " . $e->getMessage(), ['exception' => $e]);
            
            $errorMessage = $e->getMessage() === 'Jam selesai harus lebih besar dari jam mulai.' 
                            ? $e->getMessage() 
                            : 'Gagal menyimpan jadwal. Periksa log server untuk detail lebih lanjut.';

            return back()->withInput()->with('error', $errorMessage)->withErrors(['general' => $errorMessage]);
        }
    }

    /**
     * Menampilkan form untuk mengedit jadwal tertentu.
     */
    public function edit(Schedule $schedule)
    {
        // Fix for "Call to undefined method edit()"
        $doctors = DoctorDetail::with(['user', 'poli'])->get();
        $days = $this->days; 

        return view('admin.schedules.edit', compact('schedule', 'doctors', 'days'));
    }

    /**
     * Memperbarui jadwal tertentu di database.
     */
    public function update(Request $request, Schedule $schedule)
    {
        // 1. Konversi Waktu dan Hari
        $request->merge([
            'start_time' => $request->start_time ? Carbon::parse($request->start_time)->format('H:i') : null,
            'end_time' => $request->end_time ? Carbon::parse($request->end_time)->format('H:i') : null,
            'day_of_week' => strtolower($request->day_of_week), 
        ]);

        try {
            // 2. Validasi
            $validated = $this->validateScheduleRequest($request);

            // Cek apakah jadwal duplikat (mengabaikan jadwal saat ini)
            $isDuplicate = Schedule::where('doctor_id', $validated['doctor_id'])
                ->where('day_of_week', $validated['day_of_week'])
                ->where('start_time', $validated['start_time'])
                ->where('id', '!=', $schedule->id) // Abaikan ID jadwal saat ini saat cek duplikat
                ->exists();

            if ($isDuplicate) {
                return back()->withInput()->withErrors([
                    'day_of_week' => 'Dokter ini sudah memiliki jadwal pada waktu yang sama pada hari ' . $this->days[$validated['day_of_week']] . '.',
                ]);
            }

            // 3. Simpan Perubahan
            $schedule->update($validated);

            // 4. Redirect
            return redirect()->route('admin.schedules.index')
                             ->with('success', 'Jadwal Dokter berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            Log::error("Schedule Update Error: " . $e->getMessage());
            
            $errorMessage = $e->getMessage() === 'Jam selesai harus lebih besar dari jam mulai.' 
                            ? $e->getMessage() 
                            : 'Gagal memperbarui jadwal. Periksa log server.';

            return back()->withInput()->with('error', $errorMessage)->withErrors(['general' => $errorMessage]);
        }
    }


    /**
     * Validasi request untuk jadwal.
     */
    protected function validateScheduleRequest(Request $request): array
    {
        return $request->validate([
            'doctor_id' => ['required', 'exists:doctor_details,id'], 
            'day_of_week' => ['required', Rule::in(array_keys($this->days))],
            'start_time' => ['required', 'date_format:H:i'], 
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);
    }
}