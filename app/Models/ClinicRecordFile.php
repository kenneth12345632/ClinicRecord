<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicRecordFile extends Model
{
    protected $fillable = [
        'clinic_record_id',
        'path',
        'original_name',
        'size',
    ];

    public function record()
    {
        return $this->belongsTo(ClinicRecord::class, 'clinic_record_id');
    }
}

