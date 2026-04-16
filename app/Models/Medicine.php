<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
   protected $fillable = ['name', 'batch_number', 'stock', 'expiration_date'];

// This helps Laravel treat the date correctly for sorting
protected $casts = [
    'expiration_date' => 'date',
];

   
}
