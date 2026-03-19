<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\Appointment;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function store(Request $request, $appointmentId)
    {
        $validated = $request->validate([
            'diagnosis' => 'required|string',
            'doctor_notes' => 'nullable|string',
            'medications' => 'array',
            'medications.*.name' => 'required|string',
            'medications.*.dosage' => 'required|string',
            'medications.*.frequency' => 'required|string',
            'medications.*.duration' => 'required|string',
            'recommendations' => 'nullable|string',
        ]);

        $appointment = Appointment::findOrFail($appointmentId);

        if ($appointment->doctor_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $consultation = Consultation::create([
            'appointment_id' => $appointment->id,
            'diagnosis' => $validated['diagnosis'],
            'doctor_notes' => $validated['doctor_notes'] ?? null,
            'started_at' => now(), // Mocking
            'ended_at' => now(), // Mocking
        ]);

        $prescription = Prescription::create([
            'consultation_id' => $consultation->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'medications' => $validated['medications'] ?? [],
            'recommendations' => $validated['recommendations'] ?? null,
        ]);

        // auto complete appointment
        $appointment->update(['status' => 'completed']);

        return response()->json([
            'message' => 'Consultation saved successfully',
            'consultation' => $consultation,
            'prescription' => $prescription,
        ], 201);
    }
}
