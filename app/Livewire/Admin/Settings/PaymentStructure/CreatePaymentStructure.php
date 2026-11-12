<?php

namespace App\Livewire\Admin\Settings\PaymentStructure;

use App\Models\Item;
use App\Models\PaymentStructure;
use Livewire\Component;

class CreatePaymentStructure extends Component
{
    public ?int $item_id = null;
    public string $delivery_type = 'direct';
    public float $tax_percentage = 0;
    public string $pick_up_drop_off_partner_type = 'fixed'; // fixed or percentage
    public float $pick_up_drop_off_partner_amount = 0;
    public float $transport_partner_percentage = 0;
    public float $platform_percentage = 0;
    public float $platform_fixed_amount = 0;

    public function mount()
    {
        // Defaults set via property initializers
    }

    public function submit()
    {
        $this->validate([
            'item_id' => 'required|exists:items,id|unique:payment_structures,item_id',
            'delivery_type' => 'required|string|in:direct,warehouse_split',
            'tax_percentage' => 'required|numeric|min:0',
            'pick_up_drop_off_partner_type' => 'required|string|in:fixed,percentage',
            'pick_up_drop_off_partner_amount' => 'required|numeric|min:0',
            'transport_partner_percentage' => 'required|numeric|min:0',
            'platform_percentage' => 'required|numeric|min:0',
            'platform_fixed_amount' => 'required|numeric|min:0',
        ], [
            'item_id.unique' => 'A payment structure already exists for this item.',
        ]);

        try {
            PaymentStructure::create([
                'item_id' => $this->item_id,
                'delivery_type' => $this->delivery_type,
                'tax_percentage' => $this->tax_percentage,
                'pick_up_drop_off_partner_type' => $this->pick_up_drop_off_partner_type,
                'pick_up_drop_off_partner_amount' => $this->pick_up_drop_off_partner_amount,
                'transport_partner_percentage' => $this->transport_partner_percentage,
                'platform_percentage' => $this->platform_percentage,
                'platform_fixed_amount' => $this->platform_fixed_amount,
            ]);

            return redirect()
                ->route('admin.payment-structure.index')
                ->with('success', 'Payment structure created successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create payment structure: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.payment-structure.create-payment-structure', [
            'items' => Item::orderBy('name')->get(['id', 'name']),
        ]);
    }
}