<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\AdminController;
use App\Models\Specialty;

// Public Routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/doctors', [DoctorController::class, 'index']);
Route::get('/doctors/{id}', [DoctorController::class, 'show']);

Route::get('/specialties', function() {
    return response()->json(Specialty::all());
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Patient Routes
    Route::middleware('role:patient')->group(function () {
        Route::get('/appointments', [AppointmentController::class, 'index']);
        Route::post('/appointments', [AppointmentController::class, 'store']);
        Route::put('/appointments/{id}/cancel', [AppointmentController::class, 'cancel']);
        
        Route::get('/prescriptions', [PrescriptionController::class, 'index']);
        Route::get('/prescriptions/{id}/download', [PrescriptionController::class, 'download']);
    });

    // Doctor Routes
    Route::middleware('role:doctor')->group(function () {
        Route::get('/doctor/appointments', [AppointmentController::class, 'doctorAppointments']);
        Route::put('/doctor/appointments/{id}/confirm', [AppointmentController::class, 'confirm']);
        Route::put('/doctor/appointments/{id}/complete', [AppointmentController::class, 'complete']);
        
        Route::get('/doctor/availability', [DoctorController::class, 'getAvailability']);
        Route::put('/doctor/availability', [DoctorController::class, 'updateAvailability']);
        Route::post('/consultations/{appointmentId}/prescription', [ConsultationController::class, 'store']);
        
        Route::get('/doctor/patients', [DoctorController::class, 'getPatients']);
        Route::get('/doctor/stats', [DoctorController::class, 'getStats']);
    });

    // Admin Routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/stats', [AdminController::class, 'stats']);
        Route::get('/admin/doctors/pending', [AdminController::class, 'pendingDoctors']);
        Route::put('/admin/doctors/{id}/verify', [AdminController::class, 'verifyDoctor']);
        
        Route::get('/admin/users', [AdminController::class, 'users']);
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
        
        Route::get('/admin/specialties', [AdminController::class, 'specialties']);
        Route::post('/admin/specialties', [AdminController::class, 'storeSpecialty']);
        Route::put('/admin/specialties/{id}', [AdminController::class, 'updateSpecialty']);
        Route::delete('/admin/specialties/{id}', [AdminController::class, 'deleteSpecialty']);
    });
});
