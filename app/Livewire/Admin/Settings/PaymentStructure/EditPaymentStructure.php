<?php

namespace App\Livewire\Admin\Settings\PaymentStructure;

use App\Models\Item;
use App\Models\PaymentStructure;
use Livewire\Component;

class EditPaymentStructure extends Component
{
    public PaymentStructure $paymentStructure;

    public ?int $item_id = null;
    public string $delivery_type = 'direct';
    public float $tax_percentage = 0;
    public string $pick_up_drop_off_partner_type = 'fixed';
    public float $pick_up_drop_off_partner_amount = 0;
    public float $transport_partner_percentage = 0;
    public float $platform_percentage = 0;
    public float $platform_fixed_amount = 0;

    public function mount(int $id)
    {
        $this->paymentStructure = PaymentStructure::findOrFail($id);

        $this->item_id = $this->paymentStructure->item_id;
        $this->delivery_type = $this->paymentStructure->delivery_type;
        $this->tax_percentage = (float) $this->paymentStructure->tax_percentage;
        $this->pick_up_drop_off_partner_type = $this->paymentStructure->pick_up_drop_off_partner_type;
        $this->pick_up_drop_off_partner_amount = (float) $this->paymentStructure->pick_up_drop_off_partner_amount;
        $this->transport_partner_percentage = (float) $this->paymentStructure->transport_partner_percentage;
        $this->platform_percentage = (float) $this->paymentStructure->platform_percentage;
        $this->platform_fixed_amount = (float) $this->paymentStructure->platform_fixed_amount;
    }

    public function submit()
    {
        $this->validate([
            'item_id' => 'required|exists:items,id',
            'delivery_type' => [
                'required',
                'string',
                'in:direct,warehouse_split',
                // Unique per (item_id, delivery_type), excluding the current record
                function ($attribute, $value, $fail) {
                    $exists = PaymentStructure::where('item_id', $this->item_id)
                        ->where('delivery_type', $value)
                        ->where('id', '!=', $this->paymentStructure->id)
                        ->exists();

                    if ($exists) {
                        $fail('A ' . $value . ' payment structure already exists for this item.');
                    }
                },
            ],
            'tax_percentage' => 'required|numeric|min:0',
            'pick_up_drop_off_partner_type' => 'required|string|in:fixed,percentage',
            'pick_up_drop_off_partner_amount' => 'required|numeric|min:0',
            'transport_partner_percentage' => 'required|numeric|min:0',
            'platform_percentage' => 'required|numeric|min:0',
            'platform_fixed_amount' => 'required|numeric|min:0',
        ]);

        try {
            $this->paymentStructure->update([
                'item_id' => $this->item_id,
                'delivery_type' => $this->delivery_type,
                'tax_percentage' => $this->tax_percentage,
                'pick_up_drop_off_partner_type' => $this->pick_up_drop_off_partner_type,
                // NOTE: trailing space typo from the original is fixed here
                'pick_up_drop_off_partner_amount' => $this->pick_up_drop_off_partner_amount,
                'transport_partner_percentage' => $this->transport_partner_percentage,
                'platform_percentage' => $this->platform_percentage,
                'platform_fixed_amount' => $this->platform_fixed_amount,
            ]);

            return redirect()
                ->route('admin.payment-structure.index')
                ->with('success', 'Payment structure updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update payment structure: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        // Items that are either the current item, OR don't yet have a
        // structure for the currently selected delivery_type.
        $items = Item::where(function ($query) {
                $query->where('id', $this->paymentStructure->item_id)
                    ->orWhereDoesntHave('paymentStructures', function ($q) {
                        $q->where('delivery_type', $this->delivery_type);
                    });
            })
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('livewire.admin.settings.payment-structure.edit-payment-structure', [
            'items' => $items,
        ]);
    }
}