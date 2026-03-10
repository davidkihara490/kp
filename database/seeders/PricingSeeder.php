<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Pricing;
use App\Models\PricingItem;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PricingSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $zones = Zone::take(7)->get();
            $items = Item::take(6)->get();

            if ($zones->count() < 7 || $items->count() < 6) {
                throw new \Exception('You need at least 7 zones and 10 items in the database.');
            }

            // Define weight ranges
            $weightRanges = [
                ['min' => 0,  'max' => 1],
                ['min' => 1,  'max' => 5],
                ['min' => 5,  'max' => 15],
                ['min' => 15, 'max' => 30],
                ['min' => 30, 'max' => 50],
                ['min' => 50, 'max' => 100],
            ];

            foreach ($items as $item) {

                foreach ($weightRanges as $range) {

                    // Create pricing per weight range
                    $pricing = Pricing::create([
                        'item_id' => $item->id,
                        'min_weight' => $range['min'],
                        'max_weight' => $range['max'],
                    ]);

                    $pricingItems = [];

                    foreach ($zones as $sourceZone) {
                        foreach ($zones as $destinationZone) {

                            // Optional: skip same zone
                            // if ($sourceZone->id === $destinationZone->id) continue;

                            // Cost increases with weight range
                            $weightFactor = $range['max'];
                            $distanceFactor = abs($sourceZone->id - $destinationZone->id);

                            $cost = 200 + ($weightFactor * 5) + ($distanceFactor * 50);

                            $pricingItems[] = [
                                'pricing_id' => $pricing->id,
                                'source_zone_id' => $sourceZone->id,
                                'destination_zone_id' => $destinationZone->id,
                                'cost' => $cost,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }

                    PricingItem::insert($pricingItems);
                }
            }
        });
    }
}
