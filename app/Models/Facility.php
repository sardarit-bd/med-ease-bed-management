<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Facility extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'type', 'timezone'];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function beds(): HasManyThrough
    {
        return $this->hasManyThrough(Bed::class, Service::class);
    }
}
