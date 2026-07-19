<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\MedicalServiceController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SpecialtyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboard;
use App\Http\Controllers\Doctor\ExaminationController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboard;
use App\Http\Controllers\Patient\HistoryController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Receptionist\AppointmentController as ReceptionistAppointmentController;
use App\Http\Controllers\Receptionist\DashboardController as ReceptionistDashboard;
use App\Http\Controllers\Receptionist\InvoiceController;
use App\Http\Controllers\Receptionist\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/chuyen-khoa', [PublicController::class, 'specialties'])->name('specialties.index');
Route::get('/bac-si', [PublicController::class, 'doctors'])->name('doctors.index');
Route::get('/bac-si/{doctor}', [PublicController::class, 'doctor'])->name('doctors.show');

Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [LoginController::class, 'show'])->name('login');
    Route::post('/dang-nhap', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/dang-ky', [RegisterController::class, 'show'])->name('register');
    Route::post('/dang-ky', [RegisterController::class, 'register'])->name('register.submit');
});
Route::post('/dang-xuat', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/ho-so', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/ho-so', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/doi-mat-khau', [ProfileController::class, 'password'])->name('profile.password');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::resource('specialties', SpecialtyController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('doctors', AdminDoctorController::class)->only(['index', 'store', 'update']);
    Route::resource('schedules', ScheduleController::class)->only(['index', 'store', 'destroy']);
    Route::resource('services', MedicalServiceController::class)->parameters(['services' => 'medicalService'])->only(['index', 'store', 'update']);
    Route::resource('medicines', MedicineController::class)->only(['index', 'store', 'update']);
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

Route::prefix('receptionist')->name('receptionist.')->middleware(['auth', 'role:receptionist,admin'])->group(function () {
    Route::get('/dashboard', ReceptionistDashboard::class)->name('dashboard');
    Route::resource('patients', PatientController::class)->only(['index', 'store', 'update', 'show']);
    Route::get('/appointments', [ReceptionistAppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [ReceptionistAppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/appointments/{appointment}/confirm', [ReceptionistAppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::post('/appointments/{appointment}/cancel', [ReceptionistAppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::post('/appointments/{appointment}/check-in', [ReceptionistAppointmentController::class, 'checkIn'])->name('appointments.check-in');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
    Route::post('/invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
});

Route::prefix('doctor')->name('doctor.')->middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/dashboard', DoctorDashboard::class)->name('dashboard');
    Route::get('/examinations/{ticket}', [ExaminationController::class, 'show'])->name('examinations.show');
    Route::post('/examinations/{ticket}/start', [ExaminationController::class, 'start'])->name('examinations.start');
    Route::put('/records/{record}', [ExaminationController::class, 'update'])->name('records.update');
    Route::post('/records/{record}/prescription', [ExaminationController::class, 'prescription'])->name('prescriptions.store');
    Route::post('/records/{record}/complete', [ExaminationController::class, 'complete'])->name('examinations.complete');
});

Route::prefix('patient')->name('patient.')->middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/dashboard', PatientDashboard::class)->name('dashboard');
    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/appointments/create', [PatientAppointmentController::class, 'create'])->name('appointments.create');
    Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');
    Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
    Route::get('/records/{record}', [HistoryController::class, 'record'])->name('records.show');
    Route::get('/prescriptions/{prescription}', [HistoryController::class, 'prescription'])->name('prescriptions.show');
    Route::get('/invoices', [HistoryController::class, 'invoices'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [HistoryController::class, 'invoice'])->name('invoices.show');
});
