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
use App\Http\Controllers\Admin\UserController as AdminUser;

// Doctor Controllers
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboard;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointment;
use App\Http\Controllers\Doctor\MedicalRecordController as DoctorRecord;
use App\Http\Controllers\Doctor\ScheduleController as DoctorSchedule;
use App\Http\Controllers\Doctor\ProfileController as DoctorProfile; 

// Patient Controllers
use App\Http\Controllers\Patient\DashboardController as PatientDashboard;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointment;
use App\Http\Controllers\Patient\MedicalRecordController as PatientRecord;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Patient\DoctorScheduleController;
use App\Http\Controllers\Patient\PatientProfileController;

/*
|--------------------------------------------------------------------------
| PUBLIC / GUEST ROUTES
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Register (hanya untuk pasien)
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Landing utama
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        if (in_array($role, ['admin', 'doctor', 'patient'])) {
            return redirect()->route("$role.dashboard");
        }
    }
    return redirect()->route('guest.home');
})->name('landing');

// Halaman publik
Route::get('/home', [HomeController::class, 'index'])->name('guest.home');
Route::get('/doctors', [PublicDoctorController::class, 'index'])->name('public.doctors');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Redirect /dashboard ke dashboard role masing-masing
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        if (in_array($role, ['admin', 'doctor', 'patient'])) {
            return redirect()->route("$role.dashboard");
        }
        return redirect()->route('guest.home');
    })->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

            Route::resource('polis', PoliController::class)->except(['show']);
            Route::resource('doctors', AdminDoctorController::class)->except(['show']);
            Route::resource('patients', PatientController::class)->except(['show']);
            Route::resource('medicines', MedicineController::class)->except(['show']);
            Route::resource('users', AdminUser::class)->except(['show']);
            Route::get('appointments', [AdminAppointment::class, 'index'])->name('appointments.index');
            Route::patch('appointments/{appointment}/status', [AdminAppointment::class, 'updateStatus'])
                ->name('appointments.update-status');
        });

    /*
    |--------------------------------------------------------------------------
    | DOCTOR ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:doctor')
        ->prefix('doctor')
        ->name('doctor.')
        ->group(function () {

            Route::get('/dashboard', [DoctorDashboard::class, 'index'])->name('dashboard');
            Route::resource('appointments', DoctorAppointment::class)->only(['index', 'show', 'update']);
            Route::resource('medical_records', DoctorRecord::class)->except(['destroy']);
            Route::resource('schedules', DoctorSchedule::class)->except(['show']);
            Route::get('/profile', [DoctorProfile::class, 'edit'])->name('profile');
            Route::put('/profile', [DoctorProfile::class, 'update'])->name('profile.update');
        });

    /*
    |--------------------------------------------------------------------------
    | PATIENT ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:patient')
        ->prefix('patient')
        ->name('patient.')
        ->group(function () {

            Route::get('/dashboard', [PatientDashboard::class, 'index'])->name('dashboard');

            Route::resource('appointments', PatientAppointment::class)
                ->only(['index', 'create', 'store', 'show']);

            Route::get('appointments/by-poli/{poli}', [PatientAppointment::class, 'getDoctorsByPoli'])
                ->name('appointments.byPoli');

            Route::resource('medical_records', PatientRecord::class)
                ->only(['index', 'show']);

            Route::resource('doctor_schedules', DoctorScheduleController::class)
                ->only(['index', 'show']);
            
            Route::get('/profile', [PatientProfileController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [PatientProfileController::class, 'update'])->name('profile.update');
        });

});



require __DIR__.'/auth.php';
