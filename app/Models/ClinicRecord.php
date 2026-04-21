<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicRecord extends Model
{
    use HasFactory;

protected $fillable = [
    'first_name', 
    'middle_name', 
    'last_name', 
    'birthday', 
    'age', 
    'gender', 
    'civil_status', 
    'address_purok', 
    'consultation_date',
    'temp',   // Add this
    'bp',     // Add this
    'pr',     // Add this
    'rr',     // Add this
    'weight', // Add this
    'height', // Add this
    'bmi',    // Add this
    'subjective', 
    'objective', 
    'diagnosis'
];
    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'clinic_record_medicine')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}