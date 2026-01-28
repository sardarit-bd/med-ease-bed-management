<?php

namespace Database\Seeders;

use App\Models\Bed;
use App\Models\Facility;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Admission;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BedManagementSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the Facility (Lariboisière Hospital)
        $hospital = Facility::create([
            'name' => 'Lariboisière Hospital',
            'type' => 'Hospital',
            'timezone' => 'Europe/Paris',
        ]);

        // 2. Create Services (Medicine, Surgery, Resuscitation)
        $medService = Service::create(['facility_id' => $hospital->id, 'name' => 'Medicine', 'code' => 'MED']);
        $surgService = Service::create(['facility_id' => $hospital->id, 'name' => 'Surgery', 'code' => 'SURG']);
        $resusService = Service::create(['facility_id' => $hospital->id, 'name' => 'Resuscitation', 'code' => 'RESUS']);

        // --- ROW 1: L-101 (Available) ---
        Bed::create([
            'service_id' => $medService->id,
            'label' => 'L-101',
            'status' => 'available',
            'last_status_change_at' => Carbon::today()->setHour(10)->setMinute(30),
        ]);

        // --- ROW 2: L-102 (Busy - Jean Dupont) ---
        $bed102 = Bed::create([
            'service_id' => $medService->id,
            'label' => 'L-102',
            'status' => 'occupied',
            'last_status_change_at' => Carbon::today()->setHour(9)->setMinute(15),
        ]);
        
        $patientDupont = Patient::create(['first_name' => 'Jean', 'last_name' => 'Dupont', 'mrn' => 'MRN-001']);
        
        Admission::create([
            'patient_id' => $patientDupont->id,
            'bed_id' => $bed102->id,
            'status' => 'active',
            'admitted_at' => Carbon::today()->setHour(9)->setMinute(15),
        ]);

        // --- ROW 3: L-103 (Reserve - Martin Marie) ---
        // Note: Status is 'reserved', but we also need a 'planned' admission for the future.
        $bed103 = Bed::create([
            'service_id' => $medService->id,
            'label' => 'L-103',
            'status' => 'reserved',
            'last_status_change_at' => Carbon::today()->setHour(11)->setMinute(00),
        ]);

        $patientMarie = Patient::create(['first_name' => 'Marie', 'last_name' => 'MARTIN', 'mrn' => 'MRN-002']);

        Admission::create([
            'patient_id' => $patientMarie->id,
            'bed_id' => $bed103->id,
            'status' => 'planned', // Future admission
            'expected_arrival_at' => Carbon::today()->setHour(14)->setMinute(00), // "14:00"
        ]);

        // --- ROW 4: L-104 (Cleaning - BERNARD Paul Next) ---
        // The bed is dirty, but Paul is already scheduled to come at 15:00.
        $bed104 = Bed::create([
            'service_id' => $medService->id,
            'label' => 'L-104',
            'status' => 'cleaning',
            'last_status_change_at' => Carbon::today()->setHour(11)->setMinute(30),
        ]);

        $patientPaul = Patient::create(['first_name' => 'Paul', 'last_name' => 'BERNARD', 'mrn' => 'MRN-003']);

        Admission::create([
            'patient_id' => $patientPaul->id,
            'bed_id' => $bed104->id,
            'status' => 'planned',
            'expected_arrival_at' => Carbon::today()->setHour(15)->setMinute(00),
        ]);

        // --- ROW 5: C-201 (Available) ---
        Bed::create([
            'service_id' => $surgService->id,
            'label' => 'C-201',
            'status' => 'available',
            'last_status_change_at' => Carbon::today()->setHour(10)->setMinute(00),
        ]);

        // --- ROW 6: C-202 (Busy - GARCIA Ana) ---
        $bed202 = Bed::create([
            'service_id' => $surgService->id,
            'label' => 'C-202',
            'status' => 'occupied',
            'last_status_change_at' => Carbon::today()->setHour(8)->setMinute(00),
        ]);

        $patientGarcia = Patient::create(['first_name' => 'Ana', 'last_name' => 'GARCIA', 'mrn' => 'MRN-004']);

        Admission::create([
            'patient_id' => $patientGarcia->id,
            'bed_id' => $bed202->id,
            'status' => 'active',
            'admitted_at' => Carbon::today()->setHour(8)->setMinute(00),
        ]);

        // --- ROW 7: C-203 (Busy - LEMOINE Pierre) ---
        $bed203 = Bed::create([
            'service_id' => $surgService->id,
            'label' => 'C-203',
            'status' => 'occupied',
            'last_status_change_at' => Carbon::today()->setHour(7)->setMinute(30),
        ]);

        $patientLemoine = Patient::create(['first_name' => 'Pierre', 'last_name' => 'LEMOINE', 'mrn' => 'MRN-005']);

        Admission::create([
            'patient_id' => $patientLemoine->id,
            'bed_id' => $bed203->id,
            'status' => 'active',
            'admitted_at' => Carbon::today()->setHour(7)->setMinute(30),
        ]);

        // --- ROW 8: R-301 (Busy - SMITH John) ---
        $bed301 = Bed::create([
            'service_id' => $resusService->id,
            'label' => 'R-301',
            'status' => 'occupied',
            'last_status_change_at' => Carbon::today()->startOfDay(), // 00:00
        ]);

        $patientSmith = Patient::create(['first_name' => 'John', 'last_name' => 'SMITH', 'mrn' => 'MRN-006']);

        Admission::create([
            'patient_id' => $patientSmith->id,
            'bed_id' => $bed301->id,
            'status' => 'active',
            'admitted_at' => Carbon::today()->startOfDay(),
        ]);
        
        // --- ROW 9: R-302 (Busy - CHEN Li) ---
        $bed302 = Bed::create([
            'service_id' => $resusService->id,
            'label' => 'R-302',
            'status' => 'occupied',
            'last_status_change_at' => Carbon::today()->setHour(2)->setMinute(00),
        ]);

        $patientChen = Patient::create(['first_name' => 'Li', 'last_name' => 'CHEN', 'mrn' => 'MRN-007']);

        Admission::create([
            'patient_id' => $patientChen->id,
            'bed_id' => $bed302->id,
            'status' => 'active',
            'admitted_at' => Carbon::today()->setHour(2)->setMinute(00),
        ]);

        // --- ROW 10: R-303 (Available) ---
        Bed::create([
            'service_id' => $resusService->id,
            'label' => 'R-303',
            'status' => 'available',
            'last_status_change_at' => Carbon::today()->setHour(11)->setMinute(45),
        ]);
    }
}