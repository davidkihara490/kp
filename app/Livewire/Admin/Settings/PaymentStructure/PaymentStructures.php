<?php

namespace App\Livewire\Admin\Settings\PaymentStructure;

use App\Models\PaymentStructure;
use Livewire\Component;

class PaymentStructures extends Component
{
    public function render()
    {
        $paymentStructures = PaymentStructure::all();
        return view(
            'livewire.admin.settings.payment-structure.payment-structures',
            [
                'paymentStructures' => $paymentStructures
            ]
        );
    }
}
