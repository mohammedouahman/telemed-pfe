<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\Specialty;
use App\Models\Consultation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function stats()
    {
        return response()->json([
            'total_patients' => User::where('role', 'patient')->count(),
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_consultations' => Consultation::count(),
            'pending_doctors' => DoctorProfile::where('is_verified', false)->count(),
        ]);
    }

    public function pendingDoctors()
    {
        $doctors = User::with('doctorProfile')
            ->where('role', 'doctor')
            ->whereHas('doctorProfile', function ($q) {
                $q->where('is_verified', false);
            })->get();
        return response()->json($doctors);
    }

    public function verifyDoctor($id)
    {
        $doctor = User::with('doctorProfile')->where('role', 'doctor')->findOrFail($id);
        if ($doctor->doctorProfile) {
            $doctor->doctorProfile->update(['is_verified' => true]);
        }
        return response()->json(['message' => 'Doctor verified successfully']);
    }

    public function users()
    {
        $users = User::with(['doctorProfile', 'patientProfile'])->get();
        return response()->json($users);
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function specialties()
    {
        return response()->json(Specialty::all());
    }

    public function storeSpecialty(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string']);
        $spec = Specialty::create($validated);
        return response()->json($spec, 201);
    }

    public function updateSpecialty(Request $request, $id)
    {
        $validated = $request->validate(['name' => 'required|string']);
        $spec = Specialty::findOrFail($id);
        $spec->update($validated);
        return response()->json($spec);
    }

    public function deleteSpecialty($id)
    {
        Specialty::findOrFail($id)->delete();
        return response()->json(['message' => 'Specialty deleted']);
    }
}
