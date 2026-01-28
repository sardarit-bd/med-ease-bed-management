<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admission extends Model
{
    use HasUuids;

    protected $fillable = [
        'patient_id', 
        'bed_id', 
        'status',
        'expected_arrival_at',
        'admitted_at',
        'discharged_at',
        'notes'
    ];

    protected $casts = [
        'expected_arrival_at' => 'datetime',
        'admitted_at' => 'datetime',
        'discharged_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }
}
