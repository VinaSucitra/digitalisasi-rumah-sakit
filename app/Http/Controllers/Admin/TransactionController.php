<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Menampilkan daftar semua transaksi dengan fitur pencarian dan filter status.
     */
    public function index(Request $request)
    {
        // Query dasar dengan relasi yang dibutuhkan (eager loading)
        $transactions = Transaction::with([
            'appointment.patient',
            'appointment.doctor',
            'appointment.poli'
        ])->orderByDesc('created_at');

        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $transactions->where('status', $request->status);
        }

        // Filter pencarian (berdasarkan nama pasien/dokter atau ref number)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $transactions->where(function ($query) use ($searchTerm) {
                // Mencari di tabel Transaction berdasarkan reference_number
                $query->where('reference_number', 'like', $searchTerm)
                      // Mencari di tabel User (Patient) melalui relasi Appointment
                      ->orWhereHas('appointment.patient', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', $searchTerm);
                      })
                      // Mencari di tabel User (Doctor) melalui relasi Appointment
                      ->orWhereHas('appointment.doctor', function ($q) use ($searchTerm) {
                          $q->where('name', 'like', $searchTerm);
                      });
            });
        }

        $transactions = $transactions->paginate(15)->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan formulir untuk mencatat Transaksi baru.
     */
    public function create()
    {
        // Ambil semua janji temu yang belum lunas (belum punya transaksi 'paid')
        $pendingAppointments = Appointment::where('status', 'completed')
            ->doesntHave('transaction', function($q) {
                $q->where('status', 'paid');
            })
            ->with(['patient', 'doctor'])
            ->get();

        // Ambil semua janji temu yang sudah memiliki transaksi, tapi statusnya masih 'pending'
        $pendingTransactions = Transaction::where('status', 'pending')
            ->get(['appointment_id']);
        
        $hasPending = $pendingTransactions->pluck('appointment_id')->toArray();
        
        // Gabungkan hanya appointment yang sudah selesai dan belum memiliki transaksi 'paid' atau 'pending'
        // Jika appointment_id ada di $hasPending, berarti sudah ada transaksi yang dicatat, jadi jangan tampilkan di form "Create"
        $appointments = $pendingAppointments->filter(function ($appointment) use ($hasPending) {
            return !in_array($appointment->id, $hasPending);
        });

        return view('admin.transactions.create', compact('appointments'));
    }

    /**
     * Menyimpan transaksi baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0|max:' . $request->total_amount,  // Pastikan paid_amount tidak lebih besar dari total_amount
            'payment_method' => 'required|in:cash,transfer,card',
            'status' => 'required|in:pending,paid,canceled',
            'notes' => 'nullable|string|max:255',
        ]);
        
        // Cek duplikasi: Pastikan tidak ada transaksi 'paid' untuk appointment_id yang sama
        if ($validatedData['status'] === 'paid' && 
            Transaction::where('appointment_id', $validatedData['appointment_id'])
                       ->where('status', 'paid')
                       ->exists()) {
            return back()->withInput()->withErrors(['appointment_id' => 'Janji Temu ini sudah memiliki transaksi yang lunas (paid).']);
        }

        // Generate Reference Number
        $validatedData['reference_number'] = 'TRX-' . time() . '-' . rand(100, 999);

        DB::beginTransaction();
        try {
            // Simpan transaksi
            $transaction = Transaction::create($validatedData);

            // Jika statusnya PAID, update status Janji Temu jika perlu (opsional)
            if ($validatedData['status'] === 'paid') {
                 Appointment::find($validatedData['appointment_id'])->update(['payment_status' => 'paid']);
            }

            DB::commit();
            return redirect()->route('admin.transactions.index')
                             ->with('success', 'Transaksi berhasil dicatat dengan nomor referensi: ' . $validatedData['reference_number']);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal mencatat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail transaksi.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['appointment.patient', 'appointment.doctor', 'appointment.poli']);
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Menampilkan formulir untuk mengedit transaksi.
     */
    public function edit(Transaction $transaction)
    {
        return view('admin.transactions.edit', compact('transaction'));
    }

    /**
     * Memperbarui transaksi yang ada.
     */
    public function update(Request $request, Transaction $transaction)
    {
        $validatedData = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0|max:' . $request->total_amount,  // Pastikan paid_amount tidak lebih besar dari total_amount
            'payment_method' => 'required|in:cash,transfer,card',
            'status' => 'required|in:pending,paid,canceled',
            'notes' => 'nullable|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            $transaction->update($validatedData);

            // Jika status berubah menjadi PAID, update status Janji Temu
            if ($validatedData['status'] === 'paid') {
                 $transaction->appointment->update(['payment_status' => 'paid']);
            }

            DB::commit();
            return redirect()->route('admin.transactions.index')
                             ->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus transaksi.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        
        return redirect()->route('admin.transactions.index')
                         ->with('success', 'Transaksi berhasil dihapus.');
    }
}
