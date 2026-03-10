<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'name' => 'Kenya Commercial Bank (KCB)',
                'swift_code' => 'KCBLKENX',
                'bank_code' => '01',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Equity Bank',
                'swift_code' => 'EQBLKENA',
                'bank_code' => '68',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Co-operative Bank of Kenya',
                'swift_code' => 'KCOOKENA',
                'bank_code' => '11',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Absa Bank Kenya',
                'swift_code' => 'BARCKENX',
                'bank_code' => '03',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Standard Chartered Bank Kenya',
                'swift_code' => 'SCBLKENX',
                'bank_code' => '02',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'NCBA Bank Kenya',
                'swift_code' => 'CBAFKENX',
                'bank_code' => '07',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Diamond Trust Bank (DTB)',
                'swift_code' => 'DTKEKENA',
                'bank_code' => '63',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Stanbic Bank Kenya',
                'swift_code' => 'SBICKENX',
                'bank_code' => '31',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'I&M Bank',
                'swift_code' => 'IMBLKENA',
                'bank_code' => '57',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Citi Bank Kenya',
                'swift_code' => 'CITIKENX',
                'bank_code' => '30',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Bank of Africa Kenya',
                'swift_code' => 'AFRIKENA',
                'bank_code' => '41',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'National Bank of Kenya',
                'swift_code' => 'NBKEKENA',
                'bank_code' => '12',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Prime Bank',
                'swift_code' => 'PRIBKENA',
                'bank_code' => '40',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Credit Bank',
                'swift_code' => 'CREDKENA',
                'bank_code' => '56',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Family Bank',
                'swift_code' => 'FAMLKENA',
                'bank_code' => '70',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Guardian Bank',
                'swift_code' => 'GUABKENA',
                'bank_code' => '52',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Victoria Commercial Bank',
                'swift_code' => 'VICTKENA',
                'bank_code' => '54',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Housing Finance Company of Kenya (HF Group)',
                'swift_code' => 'HFCOKENA',
                'bank_code' => '50',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'GT Bank Kenya',
                'swift_code' => 'GTBNKENA',
                'bank_code' => '53',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Mayfair Bank',
                'swift_code' => 'MFBLKENA',
                'bank_code' => '65',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Sidian Bank',
                'swift_code' => 'KREOKENA',
                'bank_code' => '66',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Bank of Baroda Kenya',
                'swift_code' => 'BARBKENA',
                'bank_code' => '58',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Bank of India Kenya',
                'swift_code' => 'BKIDKENA',
                'bank_code' => '51',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Consolidated Bank of Kenya',
                'swift_code' => 'CONSKENA',
                'bank_code' => '23',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Development Bank of Kenya',
                'swift_code' => 'DBKKENA',
                'bank_code' => '71',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Kingdom Bank Kenya (formerly Jamii Bora Bank)',
                'swift_code' => 'JAMIKENA',
                'bank_code' => '67',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Middle East Bank Kenya',
                'swift_code' => 'MEBLKENA',
                'bank_code' => '59',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Paramount Bank',
                'swift_code' => 'PAMBKENA',
                'bank_code' => '48',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Spire Bank (formerly Equatorial Commercial Bank)',
                'swift_code' => 'ECBLKENA',
                'bank_code' => '49',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'UBA Kenya Bank',
                'swift_code' => 'UNAFKENA',
                'bank_code' => '55',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'M-Oriental Bank',
                'swift_code' => 'MORKENA',
                'bank_code' => '46',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Access Bank Kenya',
                'swift_code' => 'ABLAKENA',
                'bank_code' => '73',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Ecobank Kenya',
                'swift_code' => 'ECOCKENA',
                'bank_code' => '61',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'First Community Bank',
                'swift_code' => 'FCOKKENA',
                'bank_code' => '42',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'Gulf African Bank',
                'swift_code' => 'GUAFKENA',
                'bank_code' => '72',
                'country' => 'Kenya',
                'is_active' => true,
            ],
            [
                'name' => 'SBM Bank Kenya',
                'swift_code' => 'SBMOKENA',
                'bank_code' => '74',
                'country' => 'Kenya',
                'is_active' => true,
            ],
        ];

        // Insert banks into the database
        foreach ($banks as $bank) {
            Bank::create($bank);
        }

        $this->command->info(count($banks) . ' Kenyan banks seeded successfully!');
    }
}
