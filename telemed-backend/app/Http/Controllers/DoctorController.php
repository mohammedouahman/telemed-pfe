<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\DoctorAvailability;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('doctorProfile')
            ->where('role', 'doctor')
            ->whereHas('doctorProfile', function ($q) {
                $q->where('is_verified', true);
            });

        if ($request->has('specialty')) {
            $query->whereHas('doctorProfile', function ($q) use ($request) {
                $q->where('specialty', $request->specialty);
            });
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        return response()->json($query->paginate(12));
    }

    public function show($id)
    {
        $doctor = User::with(['doctorProfile', 'doctorProfile.availabilities'])->findOrFail($id);
        if ($doctor->role !== 'doctor') {
            return response()->json(['message' => 'Not a doctor'], 404);
        }
        return response()->json($doctor);
    }

    public function getAvailability(Request $request)
    {
        $availabilities = DoctorAvailability::where('doctor_id', $request->user()->id)->get();
        return response()->json($availabilities);
    }

    public function updateAvailability(Request $request)
    {
        $validated = $request->validate([
            'availabilities' => 'array',
            'availabilities.*.day_of_week' => 'required|string',
            'availabilities.*.start_time' => 'required|date_format:H:i:s',
            'availabilities.*.end_time' => 'required|date_format:H:i:s',
        ]);

        DoctorAvailability::where('doctor_id', $request->user()->id)->delete();

        foreach ($validated['availabilities'] as $avail) {
            DoctorAvailability::create([
                'doctor_id' => $request->user()->id,
                'day_of_week' => $avail['day_of_week'],
                'start_time' => $avail['start_time'],
                'end_time' => $avail['end_time'],
                'slot_duration_minutes' => 30,
            ]);
        }

        return response()->json(['message' => 'Availabilities updated successfully']);
    }

    public function getPatients(Request $request)
    {
        $doctorId = $request->user()->id;
        $patients = User::whereHas('appointmentsAsPatient', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })->with('patientProfile')->distinct()->get();

        return response()->json($patients);
    }

    public function getStats(Request $request)
    {
        $doctorId = $request->user()->id;
        $totalConsultations = $request->user()->appointmentsAsDoctor()->where('status', 'completed')->count();
        $upcoming = $request->user()->appointmentsAsDoctor()
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->count();
        $totalPatients = User::whereHas('appointmentsAsPatient', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })->distinct()->count();

        // demo revenue
        $revenue = $totalConsultations * ($request->user()->doctorProfile->consultation_fee ?? 0);

        return response()->json([
            'total_consultations' => $totalConsultations,
            'upcoming' => $upcoming,
            'total_patients' => $totalPatients,
            'revenue_month' => $revenue,
        ]);
    }
}
