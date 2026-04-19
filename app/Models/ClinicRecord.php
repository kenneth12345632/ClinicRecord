<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClinicRecord extends Model
{
    protected $fillable = [
        'first_name', 
        'middle_name', 
        'last_name', 
        'consultation_date', 
        'birthday', 
        'gender', 
        'civil_status', 
        'contact_number', 
        'address_purok', 
        'age', 
        'diagnosis', 
        'medicines_given' // Kept for legacy support, though we use the relationship now
    ];

    protected $casts = [
        'consultation_date' => 'date',
        'birthday' => 'date',
    ];

    /**
     * The medicines that belong to the clinic record.
     * This fixes the "Call to undefined relationship" error.
     */
    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class, 'clinic_record_medicine')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}