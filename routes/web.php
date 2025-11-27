<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public / Guest Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Public\DoctorController as PublicDoctorController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointment;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController as AdminUser;

// Doctor Controllers
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboard;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointment;
use App\Http\Controllers\Doctor\MedicalRecordController as DoctorRecord;

// Patient Controllers
use App\Http\Controllers\Patient\DashboardController as PatientDashboard;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointment;
use App\Http\Controllers\Patient\MedicalRecordController as PatientRecord;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

/*
|--------------------------------------------------------------------------|
| PUBLIC / GUEST ROUTES                                                    |
|--------------------------------------------------------------------------|
*/
// Route login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Landing Page (Guest) -> Jika login → redirect ke dashboard sesuai role
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        // PENTING: Gunakan redirect()->route() untuk rute bernama.
        // Role harus sesuai dengan prefix route yang didefinisikan di bawah (admin, doctor, patient).
        if (in_array($role, ['admin', 'doctor', 'patient'])) {
            return redirect()->route("$role.dashboard");
        }
    }
    return redirect()->route('guest.home'); 
})->name('landing'); // Beri nama untuk rute root agar mudah dialihkan

// Halaman guest yang sebenarnya
Route::get('/home', [HomeController::class, 'index'])->name('guest.home'); // Route untuk halaman beranda

Route::get('/doctors', [PublicDoctorController::class, 'index'])->name('public.doctors');

/*
|--------------------------------------------------------------------------|
| AUTHENTICATED ROUTES (LOGIN REQUIRED)                                    |
|--------------------------------------------------------------------------|
*/
Route::middleware('auth')->group(function () {

    // Redirect umum ke dashboard sesuai role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        // Pastikan peran valid sebelum mengalihkan
        if (in_array($role, ['admin', 'doctor', 'patient'])) {
            return redirect()->route("$role.dashboard");
        }
        // Jika peran tidak valid, arahkan ke rute default atau logout
        return redirect()->route('guest.home'); 
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------|
    | ADMIN ROUTES                                                             |
    |--------------------------------------------------------------------------|
    */
    // Pastikan peran di DB adalah 'admin'
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        
        Route::resource('polis', PoliController::class)->except(['show']);
        Route::resource('doctors', AdminDoctorController::class)->except(['show']);
        Route::resource('patients', PatientController::class)->except(['show']);
        Route::resource('medicines', MedicineController::class)->except(['show']);
        Route::resource('users', AdminUser::class)->except(['show']);
        Route::resource('appointments', AdminAppointment::class);
        Route::resource('schedules', ScheduleController::class)->except(['show']);
        Route::resource('transactions', TransactionController::class)->only(['index', 'show']);
    });

    /*
    |--------------------------------------------------------------------------|
    | DOCTOR ROUTES                                                            |
    |--------------------------------------------------------------------------|
    */
    // PENTING: Mengganti 'role:dokter' menjadi 'role:doctor' agar sesuai dengan prefix 'doctor'
    // Asumsikan role di DB adalah 'doctor'. Jika di DB adalah 'dokter', kembalikan ke 'role:dokter'
    Route::middleware('role:doctor')->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DoctorDashboard::class, 'index'])->name('dashboard');
        Route::resource('appointments', DoctorAppointment::class)->only(['index', 'show', 'update']);
        Route::resource('medical_records', DoctorRecord::class)->except(['destroy']);
    });

    /*
    |--------------------------------------------------------------------------|
    | PATIENT ROUTES                                                           |
    |--------------------------------------------------------------------------|
    */
    // PENTING: Mengganti 'role:pasien' menjadi 'role:patient'
    // Asumsikan role di DB adalah 'patient'. Jika di DB adalah 'pasien', kembalikan ke 'role:pasien'
    Route::middleware('role:patient')->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [PatientDashboard::class, 'index'])->name('dashboard');
        Route::resource('appointments', PatientAppointment::class)->only(['index', 'create', 'store', 'show']);
        Route::resource('medical_records', PatientRecord::class)->only(['index', 'show']);
    });

});

/*
|--------------------------------------------------------------------------|
| Auth routes (login/register)                                             |
|--------------------------------------------------------------------------|
*/
require __DIR__.'/auth.php';
