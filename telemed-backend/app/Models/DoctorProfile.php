<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = [
        'user_id',
        'specialty',
        'experience_years',
        'bio',
        'consultation_fee',
        'address',
        'city',
        'is_verified',
        'rating_average',
        'total_reviews',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class, 'doctor_id', 'user_id');
    }
}
