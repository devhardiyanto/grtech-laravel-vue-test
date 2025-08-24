<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();

        if ($companies->isEmpty()) {
            $this->command->warn('No companies found. Please run CompanySeeder first.');
            return;
        }

        $employees = [
            // PT Global Tech Solutions employees
            [
                'first_name' => 'Budi',
                'last_name' => 'Santoso',
                'company_id' => 1,
                'email' => 'budi.santoso@globaltech.com',
                'phone' => '+62812345678',
            ],
            [
                'first_name' => 'Siti',
                'last_name' => 'Nurhaliza',
                'company_id' => 1,
                'email' => 'siti.nurhaliza@globaltech.com',
                'phone' => '+62812345679',
            ],
            [
                'first_name' => 'Ahmad',
                'last_name' => 'Fauzi',
                'company_id' => 1,
                'email' => 'ahmad.fauzi@globaltech.com',
                'phone' => '+62812345680',
            ],
            // CV Maju Jaya Indonesia employees
            [
                'first_name' => 'Dewi',
                'last_name' => 'Lestari',
                'company_id' => 2,
                'email' => 'dewi.lestari@majujaya.co.id',
                'phone' => '+62813456789',
            ],
            [
                'first_name' => 'Rudi',
                'last_name' => 'Hermawan',
                'company_id' => 2,
                'email' => 'rudi.hermawan@majujaya.co.id',
                'phone' => '+62813456790',
            ],
            // Tech Innovators employees
            [
                'first_name' => 'John',
                'last_name' => 'Smith',
                'company_id' => 3,
                'email' => 'john.smith@techinnovators.com',
                'phone' => '+12025551234',
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'company_id' => 3,
                'email' => 'sarah.johnson@techinnovators.com',
                'phone' => '+12025551235',
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Brown',
                'company_id' => 3,
                'email' => 'michael.brown@techinnovators.com',
                'phone' => '+12025551236',
            ],
            // Digital Solutions Agency employees
            [
                'first_name' => 'Emma',
                'last_name' => 'Wilson',
                'company_id' => 4,
                'email' => 'emma.wilson@digitalsolutions.io',
                'phone' => '+447700900123',
            ],
            [
                'first_name' => 'James',
                'last_name' => 'Taylor',
                'company_id' => 4,
                'email' => 'james.taylor@digitalsolutions.io',
                'phone' => '+447700900124',
            ],
            // StartUp Hub Indonesia employees  
            [
                'first_name' => 'Andi',
                'last_name' => 'Wijaya',
                'company_id' => 5,
                'email' => 'andi.wijaya@startuphub.id',
                'phone' => '+62814567890',
            ],
            [
                'first_name' => 'Maya',
                'last_name' => 'Putri',
                'company_id' => 5,
                'email' => 'maya.putri@startuphub.id',
                'phone' => '+62814567891',
            ],
            [
                'first_name' => 'Rizki',
                'last_name' => 'Pratama',
                'company_id' => 5,
                'email' => 'rizki.pratama@startuphub.id',
                'phone' => '+62814567892',
            ],
        ];

        foreach ($employees as $employee) {
            if (isset($companies[$employee['company_id'] - 1])) {
                Employee::create($employee);
            }
        }
    }
}