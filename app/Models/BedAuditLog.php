<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BedAuditLog extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'bed_id',
        'user_id',
        'previous_status',
        'new_status',
        'changed_at',    
    ];

    protected $casts = [
        'changed_at' => 'datetime',
    ];

    /**
     * Relationship: Which bed does this log belong to?
     */
    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }

    /**
     * Relationship: Who made the change?
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}