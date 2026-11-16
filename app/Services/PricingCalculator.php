<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Partner;
use App\Models\PartnerRate;
use App\Models\PaymentStructure;
use App\Models\Town;
use App\Models\ZoneTown;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PricingCalculator
{
    /**
     * Return zone id for a town, or null.
     */
    public function zoneIdForTown(int $townId): ?int
    {
        return optional(ZoneTown::where('town_id', $townId)->first())->zone_id;
    }

    /**
     * Fetch verified transport partners that serve the route for ALL given items.
     *
     * @param int[] $itemIds
     */
    public function transportPartnersQuery(int $fromTownId, int $toTownId, array $itemIds)
    {
        $fromZoneId = $this->zoneIdForTown($fromTownId);
        $toZoneId   = $this->zoneIdForTown($toTownId);

        if (!$fromZoneId || !$toZoneId || empty($itemIds)) {
            return Partner::query()->whereRaw('1 = 0'); // empty result set
        }

        return Partner::query()
            ->where('verification_status', 'verified')
            ->where('partner_type', 'transport')
            ->whereHas('partnerRates', function ($q) use ($fromZoneId, $toZoneId, $itemIds) {
                $q->where('source_zone_id', $fromZoneId)
                    ->where('destination_zone_id', $toZoneId)
                    ->whereIn('item_id', $itemIds)
                    ->where('cost', '>', 0);
            }, '=', count($itemIds)) // require EVERY item to be priced
            ->with(['partnerRates' => function ($q) use ($fromZoneId, $toZoneId, $itemIds) {
                $q->where('source_zone_id', $fromZoneId)
                    ->where('destination_zone_id', $toZoneId)
                    ->whereIn('item_id', $itemIds);
            }]);
    }

    /**
     * Get the raw per-partner courier price for (partner, from town, to town, item).
     */
    public function courierPrice(int $partnerId, int $fromTownId, int $toTownId, int $itemId): float
    {
        $fromZone = $this->zoneIdForTown($fromTownId);
        $toZone   = $this->zoneIdForTown($toTownId);

        if (!$fromZone || !$toZone) {
            return 0.0;
        }

        $rate = PartnerRate::where([
            'partner_id'           => $partnerId,
            'source_zone_id'       => $fromZone,
            'destination_zone_id'  => $toZone,
            'item_id'              => $itemId,
        ])->first();

        return (float) ($rate->cost ?? 0);
    }

    public function paymentStructureForItem(int $itemId): ?PaymentStructure
    {
        return PaymentStructure::where('delivery_type', 'direct')
            ->where('item_id', $itemId)
            ->first();
    }

    /**
     * Compute the full breakdown for a single item for a given transport partner,
     * given an already-fetched $courierPrice.
     */
    // public function calculateItem(
    //     float $courierPrice,
    //     int $itemId,
    //     float $declaredValue = 0,
    //     bool $insuranceRequired = false
    // ): array {
    //     $ps = $this->paymentStructureForItem($itemId);

    //     $pudoAmount = 0.0;
    //     $transportFee = 0.0;
    //     $platformAmount = 0.0;
    //     $taxPct = 0.0;

    //     if ($ps) {
    //         $taxPct = (float) $ps->tax_percentage;

    //         $pudoAmount = (float) $ps->pick_up_drop_off_partner_amount * 2;
    //         $transportFee = (float) $courierPrice;
    //         $platformPercentage = (float) $ps->platform_percentage;

    //         if ($platformPercentage < 0 || $platformPercentage >= 100) {
    //             throw new \InvalidArgumentException(
    //                 'Platform percentage must be between 0 and less than 100.'
    //             );
    //         }

    //         $subtotal = $pudoAmount + $transportFee;

    //         // Gross up to 100%.
    //         $fullAmount = $subtotal / (1 - $platformPercentage / 100);

    //         $platformAmount = ($fullAmount - $subtotal)
    //             + (float) $ps->platform_fixed_amount;

    //         $totalAmount = $subtotal + $platformAmount;

    //         // $pudoAmount = (float)$ps->pick_up_drop_off_partner_amount * 2;
    //         // $transportFee = (float)$courierPrice;
    //         // $platformAmount = ($courierPrice * ((float) $ps->platform_percentage) / 100)
    //         //     + (float) $ps->platform_fixed_amount;


    //         // $pudoAmount = $ps->pick_up_drop_off_partner_type === 'percentage'
    //         //     ? ($courierPrice * ((float) $ps->pick_up_drop_off_partner_amount) / 100) *  2
    //         //     : (float) $ps->pick_up_drop_off_partner_amount * 2;

    //         // $transportFee = $courierPrice;
    //         // $platformAmount = ($courierPrice * ((float) $ps->platform_percentage) / 100)
    //         //     + (float) $ps->platform_fixed_amount;
    //     }

    //     $insurance = $insuranceRequired ? round($declaredValue * 0.02, 2) : 0.0;

    //     $subtotal = $courierPrice + $pudoAmount + $transportFee + $platformAmount + $insurance;
    //     $tax = round($subtotal * $taxPct / 100, 2);
    //     $total = round($subtotal + $tax, 2);

    //     return [
    //         'item_id'          => $itemId,
    //         'courier_price'    => round($courierPrice, 2),
    //         'pudo_amount'      => round($pudoAmount, 2),
    //         'transport_fee'    => round($transportFee, 2),
    //         'platform_amount'  => round($platformAmount, 2),
    //         'insurance'        => round($insurance, 2),
    //         'subtotal'         => round($subtotal, 2),
    //         'tax_percentage'   => $taxPct,
    //         'tax_amount'       => $tax,
    //         'total'            => $total,
    //         'declared_value'   => round($declaredValue, 2),
    //     ];
    // }

    public function calculateItem(
        float $courierPrice,
        int $itemId,
        float $declaredValue = 0,
        bool $insuranceRequired = false
    ): array {
        $ps = $this->paymentStructureForItem($itemId);

        $pudoAmount = 0.0;
        $transportFee = $courierPrice;
        $platformAmount = 0.0;
        $taxPct = 0.0;

        if ($ps) {
            $taxPct = (float) $ps->tax_percentage;
            $pudoAmount = (float) $ps->pick_up_drop_off_partner_amount * 2;
            $platformPercentage = (float) $ps->platform_percentage;

            if ($platformPercentage < 0 || $platformPercentage >= 100) {
                throw new \InvalidArgumentException(
                    'Platform percentage must be between 0 and less than 100.'
                );
            }

            // PUDO and transport represent the remaining percentage.
            $baseAmount = $pudoAmount + $transportFee;
            $fullAmount = $baseAmount / (1 - $platformPercentage / 100);

            $platformAmount = ($fullAmount - $baseAmount)
                + (float) $ps->platform_fixed_amount;
        }

        $insurance = $insuranceRequired
            ? round($declaredValue * 0.02, 2)
            : 0.0;

        $subtotal = $transportFee + $pudoAmount + $platformAmount + $insurance;
        $tax = round($subtotal * $taxPct / 100, 2);
        $total = round($subtotal + $tax, 2);

        return [
            'item_id'         => $itemId,
            'courier_price'   => (int) ceil($courierPrice),
            'pudo_amount'     => (int) ceil($pudoAmount),
            'transport_fee'   => (int) ceil($transportFee),
            'platform_amount' => (int) ceil($platformAmount),
            'insurance'       => (int) ceil($insurance),
            'subtotal'        => (int) ceil($subtotal),
            'tax_percentage'  => $taxPct,
            'tax_amount'      => (int) ceil($tax),
            'total'           => (int) ceil($total),
            'declared_value'  => (int) ceil($declaredValue),
        ];

        // return [
        //     'item_id'         => $itemId,
        //     'courier_price'   => round($courierPrice, 2),
        //     'pudo_amount'     => round($pudoAmount, 2),
        //     'transport_fee'   => round($transportFee, 2),
        //     'platform_amount' => round($platformAmount, 2),
        //     'insurance'       => round($insurance, 2),
        //     'subtotal'        => round($subtotal, 2),
        //     'tax_percentage'  => $taxPct,
        //     'tax_amount'      => $tax,
        //     'total'           => $total,
        //     'declared_value'  => round($declaredValue, 2),
        // ];
    }

    /**
     * Aggregate breakdown across multiple items for one partner.
     * Uses $partner->partnerRates (preloaded) to avoid N+1.
     *
     * @param array $items each: ['item_id'=>int, 'declared_value'=>float, 'insurance_required'=>bool]
     */
    public function calculateBooking(
        Partner $partner,
        int $fromTownId,
        int $toTownId,
        array $items
    ): array {
        $ratesByItem = $partner->partnerRates->keyBy('item_id');

        $rows = [];
        $sum = [
            'courier_price' => 0.0,
            'pudo_amount' => 0.0,
            'transport_fee' => 0.0,
            'platform_amount' => 0.0,
            'insurance' => 0.0,
            'subtotal' => 0.0,
            'tax_amount' => 0.0,
            'total' => 0.0,
        ];

        foreach ($items as $item) {
            $itemId  = (int) $item['item_id'];
            $rate    = $ratesByItem->get($itemId);
            $price   = (float) ($rate->cost ?? 0);

            if ($price <= 0) {
                // Partner doesn't price this item on this route; skip (shouldn't happen due to query)
                continue;
            }

            $row = $this->calculateItem(
                $price,
                $itemId,
                (float) ($item['declared_value'] ?? 0),
                (bool) ($item['insurance_required'] ?? false)
            );
            $rows[] = $row;
            foreach ($sum as $k => $v) {
                $sum[$k] = round($v + $row[$k], 2);
            }
        }

        return [
            'partner_id'   => $partner->id,
            'from_town_id' => $fromTownId,
            'to_town_id'   => $toTownId,
            'items'        => $rows,
            'totals'       => $sum,
        ];
    }

    /**
     * Return all available transport partners for a route, with a full breakdown
     * for the given item list.
     *
     * @param array $items each: ['item_id'=>int, 'declared_value'=>float, 'insurance_required'=>bool]
     * @return Collection
     */
    public function partnersWithBreakdown(int $fromTownId, int $toTownId, array $items): Collection
    {
        $itemIds = array_values(array_unique(array_map(fn($i) => (int) $i['item_id'], $items)));

        $partners = $this->transportPartnersQuery($fromTownId, $toTownId, $itemIds)
            ->orderBy('company_name')
            ->get();

        return $partners->map(function (Partner $partner) use ($fromTownId, $toTownId, $items) {
            $breakdown = $this->calculateBooking($partner, $fromTownId, $toTownId, $items);

            return [
                'id'             => $partner->id,
                'name'           => $partner->company_name,
                'logo'           => $partner->logo ? asset($partner->logo) : null,
                'description'    => $partner->description,
                'estimated_days' => null,
                'breakdown'      => $breakdown,
                'total'          => $breakdown['totals']['total'],
            ];
        })->filter(fn($p) => $p['total'] > 0)->values();
    }
}
