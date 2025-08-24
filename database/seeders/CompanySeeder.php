<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            [
                'name' => 'PT Global Tech Solutions',
                'email' => 'info@globaltech.com',
                'website' => 'https://globaltech.com',
            ],
            [
                'name' => 'CV Maju Jaya Indonesia',
                'email' => 'contact@majujaya.co.id',
                'website' => 'https://majujaya.co.id',
            ],
            [
                'name' => 'Tech Innovators Ltd',
                'email' => 'hello@techinnovators.com',
                'website' => 'https://techinnovators.com',
            ],
            [
                'name' => 'Digital Solutions Agency',
                'email' => 'support@digitalsolutions.io',
                'website' => 'https://digitalsolutions.io',
            ],
            [
                'name' => 'StartUp Hub Indonesia',
                'email' => 'admin@startuphub.id',
                'website' => 'https://startuphub.id',
            ],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}