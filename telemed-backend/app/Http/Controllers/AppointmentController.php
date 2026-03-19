<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $appointments = $request->user()->appointmentsAsPatient()
            ->with(['doctor', 'doctor.doctorProfile'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();
        return response()->json($appointments);
    }

    public function doctorAppointments(Request $request)
    {
        $appointments = $request->user()->appointmentsAsDoctor()
            ->with(['patient', 'patient.patientProfile'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
        return response()->json($appointments);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // check time conflict
        $conflict = Appointment::where('doctor_id', $validated['doctor_id'])
            ->where('appointment_date', $validated['appointment_date'])
            ->whereIn('status', ['pending', 'confirmed'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']]);
            })
            ->exists();

        if ($conflict) {
            return response()->json(['message' => "Ce créneau n'est plus disponible."], 422);
        }

        $appointment = Appointment::create([
            'patient_id' => $request->user()->id,
            'doctor_id' => $validated['doctor_id'],
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => 'pending',
            'video_room_id' => 'telemed-' . Str::random(10),
        ]);

        return response()->json($appointment, 201);
    }

    public function cancel(Request $request, $id)
    {
        $app = Appointment::findOrFail($id);
        if ($app->patient_id !== $request->user()->id && $app->doctor_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $app->update(['status' => 'cancelled']);
        return response()->json(['message' => 'Appointment cancelled successfully', 'appointment' => $app]);
    }

    public function confirm(Request $request, $id)
    {
        $app = Appointment::where('id', $id)->where('doctor_id', $request->user()->id)->firstOrFail();
        $app->update(['status' => 'confirmed']);
        return response()->json(['message' => 'Appointment confirmed', 'appointment' => $app]);
    }

    public function complete(Request $request, $id)
    {
        $app = Appointment::where('id', $id)->where('doctor_id', $request->user()->id)->firstOrFail();
        $app->update(['status' => 'completed']);
        return response()->json(['message' => 'Appointment completed', 'appointment' => $app]);
    }
}
