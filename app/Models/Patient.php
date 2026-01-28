<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasUuids;

    protected $fillable = ['first_name', 'last_name', 'dob', 'mrn'];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }
}
