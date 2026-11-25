<?php

namespace App\Services\ParcelApi;

use App\Models\Item;
use App\Models\Partner;
use App\Services\PricingCalculator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class BookingOptions
{
    public function __construct(private PricingCalculator $pricing) {}

    // Existing installations use either booleans or text status values.
    public static function active($query)
    {
        $column = $query->getModel()->qualifyColumn('status');
        return $query->whereRaw("cast($column as char) in (?, ?)", ['1', 'active']);
    }

    public static function items($query)
    {
        return self::active($query)->whereHas('paymentStructures')
            ->whereHas('subCategory', fn ($sub) => self::active($sub)
                ->whereHas('category', fn ($category) => self::active($category)));
    }

    public static function quoteRules(): array
    {
        return [
            'sender_town_id' => 'required|integer|exists:towns,id',
            'receiver_town_id' => 'required|integer|exists:towns,id',
            'items' => 'required|array|list|min:1',
            'items.*.item_id' => 'required|integer',
            'items.*.declared_value' => 'nullable|numeric|min:0|max:1000000',
            'items.*.insurance_required' => 'sometimes|boolean',
        ];
    }

    public function quotes(array $input, ?int $selected = null): Collection
    {
        $from = (int) $input['sender_town_id'];
        $to = (int) $input['receiver_town_id'];
        $rows = collect($input['items'])->map(fn ($row) => [
            'item_id' => (int) $row['item_id'],
            'declared_value' => (float) ($row['declared_value'] ?? 0),
            'insurance_required' => (bool) ($row['insurance_required'] ?? false),
        ]);
        $ids = $rows->pluck('item_id')->unique()->values();
        if (self::items(Item::query())->whereKey($ids->all())->count() !== $ids->count()) {
            throw ValidationException::withMessages(['items' => 'Select active bookable catalogue items.']);
        }
        $source = $this->pricing->zoneIdForTown($from);
        $destination = $this->pricing->zoneIdForTown($to);
        if (!$source || !$destination) {
            return collect();
        }
        $rates = fn ($q) => self::active($q)->where('source_zone_id', $source)
            ->where('destination_zone_id', $destination)->whereIn('item_id', $ids)->where('cost', '>', 0);
        $partners = Partner::where('partner_type', 'transport')->where('verification_status', 'verified')
            ->when($selected !== null, fn ($q) => $q->whereKey($selected))
            ->whereHas('partnerRates', $rates)->with(['partnerRates' => $rates])->orderBy('company_name')->get();
        $result = collect();
        $eligible = $this->pricing->partnersWithBreakdown($from, $to, $rows->all())->keyBy('id');
        foreach ($partners as $partner) {
            if (!$eligible->has($partner->id) || $partner->partnerRates->pluck('item_id')->unique()->count() !== $ids->count()) {
                continue;
            }
            $price = $this->pricing->calculateBooking($partner, $from, $to, $rows->all());
            if (count($price['items'] ?? []) === $rows->count() && ($price['totals']['total'] ?? 0) > 0) {
                $result->push(['partner' => $partner, 'price' => $price, 'logo' => data_get($eligible[$partner->id], 'logo')]);
            }
        }
        return $result;
    }
}
