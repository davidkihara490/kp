<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\PaymentStructure;
use App\Models\ZoneTown;
use Illuminate\Support\Collection;

class BookingQuotes
{
    public function forItems(array $data, ?int $partnerId = null): Collection
    {
        $zones = ZoneTown::whereIn('town_id', [$data['sender_town_id'], $data['receiver_town_id']])
            ->pluck('zone_id', 'town_id');
        $from = $zones->get($data['sender_town_id']);
        $to = $zones->get($data['receiver_town_id']);
        if (!$from || !$to) {
            return collect();
        }

        $ids = collect($data['items'])->pluck('parcel_category_id')->unique();
        $structures = PaymentStructure::where('delivery_type', 'direct')
            ->where(function ($q) use ($ids) {
                $q->whereIn('item_id', $ids)->orWhereNull('item_id');
            })->orderBy('id')->get();
        $default = $structures->firstWhere('item_id', null);
        $byItem = $structures->keyBy('item_id');
        $rates = fn ($q) => $q->where('source_zone_id', $from)->where('destination_zone_id', $to)
            ->whereIn('item_id', $ids)->where('status', 'active')->where('cost', '>', 0);

        $partners = Partner::where('partner_type', 'transport')->where('verification_status', 'verified')
            ->when($partnerId, fn ($q) => $q->whereKey($partnerId))
            ->withWhereHas('partnerRates', $rates)->get();

        $quotes = collect();
        foreach ($partners as $partner) {
            $prices = $partner->partnerRates->sortBy('id')->keyBy('item_id');
            $lines = [];
            foreach ($data['items'] as $key => $item) {
                $id = $item['parcel_category_id'];
                $structure = $byItem->get($id, $default);
                if (!$prices->has($id) || !$structure) {
                    continue 2;
                }
                $lines[$key] = $this->line((float) $prices[$id]->cost, $structure, $item);
            }
            $quotes->push([
                'id' => $partner->id,
                'name' => $partner->company_name,
                'logo' => $partner->logo ? asset($partner->logo) : null,
                'total' => array_sum(array_column($lines, 'total_amount')),
                'lines' => $lines,
            ]);
        }
        return $quotes->sortBy('total')->values();
    }

    private function line(float $rate, PaymentStructure $structure, array $item): array
    {
        $factors = ['fragile' => 1.2, 'valuable' => 1.2, 'perishable' => 1.3, 'hazardous' => 1.5];
        $courier = round($rate * ($factors[$item['package_type']] ?? 1), 2);
        $pointFee = (float) $structure->pick_up_drop_off_partner_amount;
        if ($structure->pick_up_drop_off_partner_type === 'percentage') {
            $pointFee = round($courier * $pointFee / 100, 2);
        }
        $platform = round($courier * $structure->platform_percentage / 100, 2) + $structure->platform_fixed_amount;
        $base = round($courier + 2 * $pointFee + $platform, 2);
        $insurance = empty($item['insurance_required']) ? 0 : round(($item['declared_value'] ?? 0) * 0.02, 2);
        $tax = round(($base + $insurance) * $structure->tax_percentage / 100, 2);
        // STK Push accepts whole shillings. Record the same payable amount we quote.
        return [
            'base_price' => $base, 'insurance_charge' => $insurance, 'insurance_amount' => $insurance,
            'tax_amount' => $tax, 'total_amount' => (int) ceil(round($base + $insurance + $tax, 2)),
        ];
    }
}
