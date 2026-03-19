<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'consultation_id',
        'patient_id',
        'doctor_id',
        'medications',
        'recommendations',
        'pdf_path',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'medications' => 'array',
            'issued_at' => 'datetime',
        ];
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
