<?php

namespace App\Livewire\Admin\Settings\PaymentStructure;

use App\Models\PaymentStructure;
use Livewire\Component;

class EditPaymentStructure extends Component
{
    public string $delivery_type;
    public  float $tax_percentage;
    public float $pick_up_drop_off_partner_percentage;
    public float $transport_partner_percentage;
    public float $platform_percentage;
    public PaymentStructure $paymentStructure;


    public function mount($id)
    {
        $this->paymentStructure = PaymentStructure::findOrFail($id);
        $this->delivery_type = $this->paymentStructure->delivery_type;
        $this->tax_percentage = $this->paymentStructure->tax_percentage;
        $this->pick_up_drop_off_partner_percentage = $this->paymentStructure->pick_up_drop_off_partner_percentage;
        $this->transport_partner_percentage = $this->paymentStructure->transport_partner_percentage;
        $this->platform_percentage = $this->paymentStructure->platform_percentage;
    }

    public function submit()
    {
        $this->validate([
            'delivery_type' => 'required|string|in:direct,warehouse_split',
            'tax_percentage' => 'required',
            'pick_up_drop_off_partner_percentage' => 'required',
            'transport_partner_percentage' => 'required',
            'platform_percentage' => 'required',

        ]);

        try {
            $this->paymentStructure->update([
                'delivery_type' => $this->delivery_type,
                'tax_percentage' => $this->tax_percentage,
                'pick_up_drop_off_partner_percentage ' => $this->pick_up_drop_off_partner_percentage,
                'transport_partner_percentage' => $this->transport_partner_percentage,
                'platform_percentage' => $this->platform_percentage,
            ]);

            return redirect()->route('admin.payment-structure.index')->with('success', 'Payment structure updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update payment structure: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.payment-structure.edit-payment-structure');
    }
}
