<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TownSeeder extends Seeder
{
    public function run(): void
    {
        $towns = [
            // Format: ['code', 'sub_county_code', 'name', 'status']
            // MOMBASA COUNTY
            ['code' => '0010101', 'sub_county_code' => '00101', 'name' => 'Changamwe', 'status' => true],
            ['code' => '0010102', 'sub_county_code' => '00101', 'name' => 'Port Reitz', 'status' => true],
            ['code' => '0010103', 'sub_county_code' => '00101', 'name' => 'Kipevu', 'status' => true],
            ['code' => '0010104', 'sub_county_code' => '00101', 'name' => 'Airport', 'status' => true],
            
            ['code' => '0010201', 'sub_county_code' => '00102', 'name' => 'Jomvu Kuu', 'status' => true],
            ['code' => '0010202', 'sub_county_code' => '00102', 'name' => 'Miritini', 'status' => true],
            
            ['code' => '0010301', 'sub_county_code' => '00103', 'name' => 'Mtopanga', 'status' => true],
            ['code' => '0010302', 'sub_county_code' => '00103', 'name' => 'Mikindani', 'status' => true],
            ['code' => '0010303', 'sub_county_code' => '00103', 'name' => 'Mji wa Kale', 'status' => true],
            
            ['code' => '0010401', 'sub_county_code' => '00104', 'name' => 'Nyali', 'status' => true],
            ['code' => '0010402', 'sub_county_code' => '00104', 'name' => 'Kongowea', 'status' => true],
            ['code' => '0010403', 'sub_county_code' => '00104', 'name' => 'Bamburi', 'status' => true],
            
            ['code' => '0010501', 'sub_county_code' => '00105', 'name' => 'Likoni', 'status' => true],
            ['code' => '0010502', 'sub_county_code' => '00105', 'name' => 'Mtongwe', 'status' => true],
            ['code' => '0010503', 'sub_county_code' => '00105', 'name' => 'Timbwani', 'status' => true],
            
            ['code' => '0010601', 'sub_county_code' => '00106', 'name' => 'Mombasa Island', 'status' => true],
            ['code' => '0010602', 'sub_county_code' => '00106', 'name' => 'Tudor', 'status' => true],
            ['code' => '0010603', 'sub_county_code' => '00106', 'name' => 'Tononoka', 'status' => true],
            ['code' => '0010604', 'sub_county_code' => '00106', 'name' => 'Majengo', 'status' => true],
            ['code' => '0010605', 'sub_county_code' => '00106', 'name' => 'Old Town', 'status' => true],

            // NAIROBI COUNTY
            ['code' => '0470101', 'sub_county_code' => '04701', 'name' => 'Westlands', 'status' => true],
            ['code' => '0470102', 'sub_county_code' => '04701', 'name' => 'Parklands', 'status' => true],
            ['code' => '0470103', 'sub_county_code' => '04701', 'name' => 'Highridge', 'status' => true],
            ['code' => '0470104', 'sub_county_code' => '04701', 'name' => 'Kangemi', 'status' => true],
            
            ['code' => '0470201', 'sub_county_code' => '04702', 'name' => 'Kawangware', 'status' => true],
            ['code' => '0470202', 'sub_county_code' => '04702', 'name' => 'Gatina', 'status' => true],
            ['code' => '0470203', 'sub_county_code' => '04702', 'name' => 'Kilimani', 'status' => true],
            
            ['code' => '0470301', 'sub_county_code' => '04703', 'name' => 'Mutuini', 'status' => true],
            ['code' => '0470302', 'sub_county_code' => '04703', 'name' => 'Ngando', 'status' => true],
            ['code' => '0470303', 'sub_county_code' => '04703', 'name' => 'Riruta', 'status' => true],
            
            ['code' => '0470401', 'sub_county_code' => '04704', 'name' => 'Karen', 'status' => true],
            ['code' => '0470402', 'sub_county_code' => '04704', 'name' => 'Nairobi West', 'status' => true],
            ['code' => '0470403', 'sub_county_code' => '04704', 'name' => 'Mugumoini', 'status' => true],
            
            ['code' => '0470501', 'sub_county_code' => '04705', 'name' => 'Kibera', 'status' => true],
            ['code' => '0470502', 'sub_county_code' => '04705', 'name' => 'Laini Saba', 'status' => true],
            ['code' => '0470503', 'sub_county_code' => '04705', 'name' => 'Makina', 'status' => true],
            
            ['code' => '0470601', 'sub_county_code' => '04706', 'name' => 'Roysambu', 'status' => true],
            ['code' => '0470602', 'sub_county_code' => '04706', 'name' => 'Garden Estate', 'status' => true],
            ['code' => '0470603', 'sub_county_code' => '04706', 'name' => 'Kahawa', 'status' => true],
            
            ['code' => '0470701', 'sub_county_code' => '04707', 'name' => 'Kasarani', 'status' => true],
            ['code' => '0470702', 'sub_county_code' => '04707', 'name' => 'Mwiki', 'status' => true],
            ['code' => '0470703', 'sub_county_code' => '04707', 'name' => 'Clay City', 'status' => true],
            ['code' => '0470704', 'sub_county_code' => '04707', 'name' => 'Njiru', 'status' => true],
            
            ['code' => '0470801', 'sub_county_code' => '04708', 'name' => 'Baba Dogo', 'status' => true],
            ['code' => '0470802', 'sub_county_code' => '04708', 'name' => 'Utalii', 'status' => true],
            ['code' => '0470803', 'sub_county_code' => '04708', 'name' => 'Mathare North', 'status' => true],
            
            ['code' => '0470901', 'sub_county_code' => '04709', 'name' => 'Imara Daima', 'status' => true],
            ['code' => '0470902', 'sub_county_code' => '04709', 'name' => 'Kwa Njenga', 'status' => true],
            
            ['code' => '0471001', 'sub_county_code' => '04710', 'name' => 'Kariobangi North', 'status' => true],
            ['code' => '0471002', 'sub_county_code' => '04710', 'name' => 'Dandora Area I', 'status' => true],
            
            ['code' => '0471101', 'sub_county_code' => '04711', 'name' => 'Kayole North', 'status' => true],
            ['code' => '0471102', 'sub_county_code' => '04711', 'name' => 'Kayole Central', 'status' => true],
            
            ['code' => '0471201', 'sub_county_code' => '04712', 'name' => 'Utawala', 'status' => true],
            ['code' => '0471202', 'sub_county_code' => '04712', 'name' => 'Mihango', 'status' => true],
            
            ['code' => '0471301', 'sub_county_code' => '04713', 'name' => 'Umoja I', 'status' => true],
            ['code' => '0471302', 'sub_county_code' => '04713', 'name' => 'Umoja II', 'status' => true],
            
            ['code' => '0471401', 'sub_county_code' => '04714', 'name' => 'Makadara', 'status' => true],
            ['code' => '0471402', 'sub_county_code' => '04714', 'name' => 'Maringo', 'status' => true],
            
            ['code' => '0471501', 'sub_county_code' => '04715', 'name' => 'Pumwani', 'status' => true],
            ['code' => '0471502', 'sub_county_code' => '04715', 'name' => 'Eastleigh North', 'status' => true],
            
            ['code' => '0471601', 'sub_county_code' => '04716', 'name' => 'Nairobi Central', 'status' => true],
            ['code' => '0471602', 'sub_county_code' => '04716', 'name' => 'Ngara', 'status' => true],
            
            ['code' => '0471701', 'sub_county_code' => '04717', 'name' => 'Mathare', 'status' => true],
            ['code' => '0471702', 'sub_county_code' => '04717', 'name' => 'Hospital', 'status' => true],

            // NAKURU COUNTY
            ['code' => '0320101', 'sub_county_code' => '03201', 'name' => 'Nakuru East', 'status' => true],
            ['code' => '0320102', 'sub_county_code' => '03201', 'name' => 'Kaptembwo', 'status' => true],
            ['code' => '0320103', 'sub_county_code' => '03201', 'name' => 'Rhoda', 'status' => true],
            
            ['code' => '0320201', 'sub_county_code' => '03202', 'name' => 'Barut', 'status' => true],
            ['code' => '0320202', 'sub_county_code' => '03202', 'name' => 'London', 'status' => true],
            ['code' => '0320203', 'sub_county_code' => '03202', 'name' => 'Kivumbini', 'status' => true],
            
            ['code' => '0320301', 'sub_county_code' => '03203', 'name' => 'Naivasha Town', 'status' => true],
            ['code' => '0320302', 'sub_county_code' => '03203', 'name' => 'Karai', 'status' => true],
            ['code' => '0320303', 'sub_county_code' => '03203', 'name' => 'Maiella', 'status' => true],
            
            ['code' => '0320401', 'sub_county_code' => '03204', 'name' => 'Gilgil Town', 'status' => true],
            ['code' => '0320402', 'sub_county_code' => '03204', 'name' => 'Elementaita', 'status' => true],
            
            ['code' => '0321101', 'sub_county_code' => '03211', 'name' => 'Njoro Town', 'status' => true],
            ['code' => '0321102', 'sub_county_code' => '03211', 'name' => 'Mau Narok', 'status' => true],

            // KISUMU COUNTY
            ['code' => '0420101', 'sub_county_code' => '04201', 'name' => 'Kisumu Central', 'status' => true],
            ['code' => '0420102', 'sub_county_code' => '04201', 'name' => 'Railways', 'status' => true],
            ['code' => '0420103', 'sub_county_code' => '04201', 'name' => 'Migosi', 'status' => true],
            
            ['code' => '0420201', 'sub_county_code' => '04202', 'name' => 'Kisumu East', 'status' => true],
            ['code' => '0420202', 'sub_county_code' => '04202', 'name' => 'Kanyakwar', 'status' => true],
            
            ['code' => '0420301', 'sub_county_code' => '04203', 'name' => 'Kisumu West', 'status' => true],
            ['code' => '0420302', 'sub_county_code' => '04203', 'name' => 'Central Seme', 'status' => true],
            
            ['code' => '0420701', 'sub_county_code' => '04207', 'name' => 'Pap Onditi', 'status' => true],
            ['code' => '0420702', 'sub_county_code' => '04207', 'name' => 'Ombeyi', 'status' => true],

            // KIAMBU COUNTY
            ['code' => '0220401', 'sub_county_code' => '02204', 'name' => 'Thika Town', 'status' => true],
            ['code' => '0220402', 'sub_county_code' => '02204', 'name' => 'Gatuanyaga', 'status' => true],
            
            ['code' => '0220501', 'sub_county_code' => '02205', 'name' => 'Ruiru Town', 'status' => true],
            ['code' => '0220502', 'sub_county_code' => '02205', 'name' => 'Githurai', 'status' => true],
            
            ['code' => '0220701', 'sub_county_code' => '02207', 'name' => 'Kiambu Town', 'status' => true],
            ['code' => '0220702', 'sub_county_code' => '02207', 'name' => 'Tigoni', 'status' => true],

            // Add more towns as needed following the same pattern...
        ];

        foreach ($towns as $town) {
            // Get the sub_county_id using the sub_county_code
            $subCounty = DB::table('sub_counties')
                ->where('code', $town['sub_county_code'])
                ->first();
            
            if ($subCounty) {
                DB::table('towns')->updateOrInsert(
                    ['code' => $town['code']],
                    [
                        'sub_county_id' => $subCounty->id,
                        'name' => $town['name'],
                        'status' => $town['status'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } else {
                // Log or handle missing sub-county
                echo "Sub-county with code {$town['sub_county_code']} not found for town {$town['name']}\n";
            }
        }
    }
}