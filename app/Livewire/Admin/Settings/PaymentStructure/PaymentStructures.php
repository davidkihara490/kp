<?php

namespace App\Livewire\Admin\Settings\PaymentStructure;

use App\Models\PaymentStructure;
use Livewire\Component;

class PaymentStructures extends Component
{
    public function delete(int $id)
    {
        try {
            PaymentStructure::findOrFail($id)->delete();
            session()->flash('success', 'Payment structure deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete payment structure: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $paymentStructures = PaymentStructure::with('item')
            ->latest()
            ->get();

        return view(
            'livewire.admin.settings.payment-structure.payment-structures',
            [
                'paymentStructures' => $paymentStructures
            ]
        );
    }
}