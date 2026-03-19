<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $prescriptions = Prescription::where('patient_id', $request->user()->id)
            ->with(['doctor', 'doctor.doctorProfile', 'consultation'])
            ->orderBy('issued_at', 'desc')
            ->get();
        return response()->json($prescriptions);
    }

    public function download(Request $request, $id)
    {
        $prescription = Prescription::with(['doctor', 'doctor.doctorProfile', 'patient', 'patient.patientProfile'])
            ->findOrFail($id);

        if ($prescription->patient_id !== $request->user()->id && $request->user()->role !== 'doctor') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $pdf = Pdf::loadView('pdf.prescription', ['prescription' => $prescription]);
        
        return $pdf->download('ordonnance_' . $prescription->id . '.pdf');
    }
}
