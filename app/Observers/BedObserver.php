<?php

namespace App\Observers;

use App\Models\Bed;
use App\Models\BedAuditLog;
use Illuminate\Support\Facades\Auth;

class BedObserver
{
    /**
     * Handle the Bed "updating" event.
     */
    public function updating(Bed $bed): void
    {
        if ($bed->isDirty('status')) {
            BedAuditLog::create([
                'bed_id' => $bed->id,
                'user_id' => Auth::id(),
                'previous_status' => $bed->getOriginal('status'),
                'new_status' => $bed->status,
                'changed_at' => now(),
            ]);

            $bed->last_status_change_at = now();
        }
    }

    /**
     * Handle the Bed "created" event.
     */
    public function created(Bed $bed): void
    {
        BedAuditLog::create([
            'bed_id' => $bed->id,
            'user_id' => Auth::id() ?? null,
            'previous_status' => null,
            'new_status' => $bed->status,
            'changed_at' => now(),
        ]);
    }

    /**
     * Handle the Bed "deleted" event.
     */
    public function deleted(Bed $bed): void
    {
        //
    }

    /**
     * Handle the Bed "restored" event.
     */
    public function restored(Bed $bed): void
    {
        //
    }

    /**
     * Handle the Bed "force deleted" event.
     */
    public function forceDeleted(Bed $bed): void
    {
        //
    }
}
