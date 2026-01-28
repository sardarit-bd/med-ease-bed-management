<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Observers\BedObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(BedObserver::class)]
class Bed extends Model
{
    use HasUuids;

    protected $fillable = [
        'service_id', 
        'label', 
        'status',   
        'last_status_change_at'
    ];

    protected $casts = [
        'last_status_change_at' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }
    
    public function activeAdmission(): HasOne
    {
        return $this->hasOne(Admission::class)
            ->where('status', 'active')
            ->latestOfMany(); 
    }

    public function futureAdmission(): HasOne
    {
        return $this->hasOne(Admission::class)
            ->where('status', 'planned')
            ->where('expected_arrival_at', '>', now())
            ->orderBy('expected_arrival_at', 'asc'); 
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', 'occupied');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
