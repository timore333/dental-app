<?php

namespace Database\Seeders;

use App\Models\Procedure;
use Illuminate\Database\Seeder;

class ProcedureSeeder extends Seeder
{
    public function run(): void
    {
        $procedures = [
            // Consultation & Examination
            [
                'code' => 'CONS-001',
                'name' => 'Initial Consultation',
                'description' => 'First visit for patient evaluation and diagnosis',
                'default_price' => 150.00,
                'category' => 'consultation',
            ],
            [
                'code' => 'CONS-002',
                'name' => 'Dental Examination',
                'description' => 'Routine dental examination and oral health assessment',
                'default_price' => 75.00,
                'category' => 'consultation',
            ],
            [
                'code' => 'CONS-003',
                'name' => 'X-Ray (Panoramic)',
                'description' => 'Full mouth panoramic X-ray',
                'default_price' => 100.00,
                'category' => 'consultation',
            ],
            [
                'code' => 'CONS-004',
                'name' => 'X-Ray (Periapical)',
                'description' => 'Single tooth X-ray',
                'default_price' => 30.00,
                'category' => 'consultation',
            ],

            // Cleaning & Scaling
            [
                'code' => 'CLEAN-001',
                'name' => 'Professional Cleaning',
                'description' => 'Dental cleaning and plaque removal',
                'default_price' => 120.00,
                'category' => 'cleaning',
            ],
            [
                'code' => 'CLEAN-002',
                'name' => 'Scaling & Root Planing',
                'description' => 'Deep cleaning for gum disease treatment',
                'default_price' => 200.00,
                'category' => 'cleaning',
            ],
            [
                'code' => 'CLEAN-003',
                'name' => 'Fluoride Treatment',
                'description' => 'Fluoride application for cavity prevention',
                'default_price' => 50.00,
                'category' => 'cleaning',
            ],

            // Fillings
            [
                'code' => 'FILL-001',
                'name' => 'Composite Filling (1 Surface)',
                'description' => 'Tooth-colored filling for single surface cavity',
                'default_price' => 150.00,
                'category' => 'filling',
            ],
            [
                'code' => 'FILL-002',
                'name' => 'Composite Filling (2 Surfaces)',
                'description' => 'Tooth-colored filling for two surface cavity',
                'default_price' => 250.00,
                'category' => 'filling',
            ],
            [
                'code' => 'FILL-003',
                'name' => 'Composite Filling (3+ Surfaces)',
                'description' => 'Tooth-colored filling for multiple surface cavity',
                'default_price' => 350.00,
                'category' => 'filling',
            ],
            [
                'code' => 'FILL-004',
                'name' => 'Silver Amalgam Filling',
                'description' => 'Traditional amalgam filling',
                'default_price' => 120.00,
                'category' => 'filling',
            ],

            // Root Canal & Endodontics
            [
                'code' => 'ROOT-001',
                'name' => 'Root Canal Treatment (Anterior)',
                'description' => 'Endodontic treatment for front tooth',
                'default_price' => 600.00,
                'category' => 'root_canal',
            ],
            [
                'code' => 'ROOT-002',
                'name' => 'Root Canal Treatment (Posterior)',
                'description' => 'Endodontic treatment for back tooth',
                'default_price' => 800.00,
                'category' => 'root_canal',
            ],
            [
                'code' => 'ROOT-003',
                'name' => 'Root Canal Re-treatment',
                'description' => 'Retreatment of previously treated tooth',
                'default_price' => 1000.00,
                'category' => 'root_canal',
            ],

            // Extraction
            [
                'code' => 'EXTR-001',
                'name' => 'Simple Extraction',
                'description' => 'Extraction of single tooth',
                'default_price' => 200.00,
                'category' => 'extraction',
            ],
            [
                'code' => 'EXTR-002',
                'name' => 'Surgical Extraction',
                'description' => 'Surgical removal of impacted or complex tooth',
                'default_price' => 500.00,
                'category' => 'extraction',
            ],
            [
                'code' => 'EXTR-003',
                'name' => 'Wisdom Tooth Extraction',
                'description' => 'Extraction of wisdom tooth',
                'default_price' => 400.00,
                'category' => 'extraction',
            ],

            // Crowns & Bridges
            [
                'code' => 'CROWN-001',
                'name' => 'Porcelain Crown',
                'description' => 'Full porcelain crown restoration',
                'default_price' => 1200.00,
                'category' => 'crown',
            ],
            [
                'code' => 'CROWN-002',
                'name' => 'Crown Preparation',
                'description' => 'Tooth preparation for crown',
                'default_price' => 300.00,
                'category' => 'crown',
            ],
            [
                'code' => 'CROWN-003',
                'name' => 'Temporary Crown',
                'description' => 'Temporary crown placement',
                'default_price' => 150.00,
                'category' => 'crown',
            ],
            [
                'code' => 'BRIDGE-001',
                'name' => 'Dental Bridge (3 Units)',
                'description' => 'Three unit bridge restoration',
                'default_price' => 2500.00,
                'category' => 'crown',
            ],

            // Implants
            [
                'code' => 'IMPL-001',
                'name' => 'Dental Implant Placement',
                'description' => 'Surgical placement of dental implant',
                'default_price' => 2000.00,
                'category' => 'implant',
            ],
            [
                'code' => 'IMPL-002',
                'name' => 'Implant Crown',
                'description' => 'Crown restoration on dental implant',
                'default_price' => 1500.00,
                'category' => 'implant',
            ],
            [
                'code' => 'IMPL-003',
                'name' => 'Bone Grafting',
                'description' => 'Bone augmentation for implant placement',
                'default_price' => 1500.00,
                'category' => 'implant',
            ],

            // Orthodontics
            [
                'code' => 'ORTHO-001',
                'name' => 'Braces - Initial Placement',
                'description' => 'Placement of orthodontic braces',
                'default_price' => 3000.00,
                'category' => 'orthodontics',
            ],
            [
                'code' => 'ORTHO-002',
                'name' => 'Braces - Monthly Adjustment',
                'description' => 'Monthly adjustment of braces',
                'default_price' => 200.00,
                'category' => 'orthodontics',
            ],
            [
                'code' => 'ORTHO-003',
                'name' => 'Clear Aligners',
                'description' => 'Custom clear aligner treatment',
                'default_price' => 4000.00,
                'category' => 'orthodontics',
            ],

            // Cosmetic
            [
                'code' => 'COSM-001',
                'name' => 'Teeth Whitening (Professional)',
                'description' => 'Professional in-office whitening',
                'default_price' => 400.00,
                'category' => 'whitening',
            ],
            [
                'code' => 'COSM-002',
                'name' => 'Teeth Whitening (Take-Home)',
                'description' => 'Custom take-home whitening kit',
                'default_price' => 250.00,
                'category' => 'whitening',
            ],
            [
                'code' => 'COSM-003',
                'name' => 'Veneer (Single)',
                'description' => 'Porcelain veneer placement',
                'default_price' => 1000.00,
                'category' => 'crown',
            ],

            // Periodontal
            [
                'code' => 'PERIO-001',
                'name' => 'Gum Grafting',
                'description' => 'Gum tissue graft procedure',
                'default_price' => 1500.00,
                'category' => 'cleaning',
            ],
            [
                'code' => 'PERIO-002',
                'name' => 'Periodontal Maintenance',
                'description' => 'Ongoing gum disease maintenance',
                'default_price' => 180.00,
                'category' => 'cleaning',
            ],
        ];

        foreach ($procedures as $procedure) {
            Procedure::create($procedure + ['is_active' => true]);
        }
    }
}
