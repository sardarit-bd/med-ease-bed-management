<?php

namespace App\Services;

use App\Models\Bed;
use Illuminate\Database\Eloquent\Collection;

class BedService
{
    public function getDashboardBeds(): Collection
    {
        return Bed::query()
            ->with([
                'service',
                'activeAdmission.patient',    
                'futureAdmission.patient'    
            ])
            ->orderBy('service_id')            
            ->orderBy('label')
            ->get();
    }
}