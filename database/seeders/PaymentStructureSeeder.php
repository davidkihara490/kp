<?php

namespace Database\Seeders;

use App\Models\PaymentStructure;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentStructureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                $structures = [
            [
                'delivery_type' => 'direct',
                'tax_percentage' => 16.00,
                'pick_up_drop_off_partner_percentage' => 20.00,
                'transport_partner_percentage' => 30.00,
                'platform_percentage' => 30.00,
            ],
            [
                'delivery_type' => 'warehouse_split',
                'tax_percentage' => 16.00,
                'pick_up_drop_off_partner_percentage' => 20.00,
                'transport_partner_percentage' => 15.00,
                'platform_percentage' => 30.00,
            ],
        ];

        foreach ($structures as $structure) {
            PaymentStructure::updateOrCreate(
                ['delivery_type' => $structure['delivery_type']],
                $structure
            );
        }
    }
}
