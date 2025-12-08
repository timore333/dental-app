<?php

namespace Database\Seeders;

use App\Models\InsuranceCompany;
use App\Models\Procedure;
use App\Models\InsuranceCompanyPriceList;
use App\Models\User;
use Illuminate\Database\Seeder;

class InsuranceCompanySeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::admin()->get()->first();

        $companies = [
            [
                'name' => 'National Health Insurance',
                'registration_number' => 'NHI-2023-001',
                'phone' => '+20-2-1234-5678',
                'email' => 'info@nhi.eg',
                'contact_person' => 'Mr. Ahmed ElDin',
                'address' => '123 Insurance Street, Cairo',
                'city' => 'Cairo',
            ],
            [
                'name' => 'United Health Care Egypt',
                'registration_number' => 'UHC-2023-002',
                'phone' => '+20-2-8765-4321',
                'email' => 'contact@uhc-egypt.com',
                'contact_person' => 'Ms. Hana Khalil',
                'address' => '456 Health Avenue, Giza',
                'city' => 'Giza',
            ],
            [
                'name' => 'Premium Insurance Group',
                'registration_number' => 'PIG-2023-003',
                'phone' => '+20-2-5555-5555',
                'email' => 'support@premium-insurance.eg',
                'contact_person' => 'Mr. Hassan Magdy',
                'address' => '789 Premium Plaza, Heliopolis',
                'city' => 'Cairo',
            ],
            [
                'name' => 'Delta Care Insurance',
                'registration_number' => 'DCI-2023-004',
                'phone' => '+20-2-3333-3333',
                'email' => 'care@deltacare.eg',
                'contact_person' => 'Ms. Laila Nasser',
                'address' => '321 Delta Street, Alexandria',
                'city' => 'Alexandria',
            ],
        ];

        $procedures = Procedure::all();

        foreach ($companies as $companyData) {
            // Create insurance company
            $company = InsuranceCompany::updateOrCreate(
                ['name' => $companyData['name']],
                [
                    'registration_number' => $companyData['registration_number'],
                    'phone' => $companyData['phone'],
                    'email' => $companyData['email'],
                    'contact_person' => $companyData['contact_person'],
                    'address' => $companyData['address'],
                    'city' => $companyData['city'],
                    'is_active' => true,
                    'created_by' => $adminUser->id,
                ]
            );

            // Create price list for each procedure
            // Price varies by insurance company (10-30% discount from default price)
            foreach ($procedures as $procedure) {
                $discountPercentage = rand(10, 30);
                $insurancePrice = $procedure->default_price * (1 - ($discountPercentage / 100));

                InsuranceCompanyPriceList::updateOrCreate(
                    [
                        'insurance_company_id' => $company->id,
                        'procedure_id' => $procedure->id,
                    ],
                    [
                        'price' => round($insurancePrice, 2),
                    ]
                );
            }
        }
    }
}
