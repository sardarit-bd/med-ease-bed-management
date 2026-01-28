<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    use HasUuids;

    protected $fillable = ['facility_id', 'name', 'code'];

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class);
    }
}
