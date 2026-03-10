<?php

namespace App\Livewire\Admin\Payments;

use App\Models\Payment;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Payments extends Component
{
    use WithPagination;

    // Search filters
    public $search = '';
    public $reference = '';
    public $mpesaCode = '';
    public $paidBy = '';
    public $status = '';
    public $paymentMethod = '';
    
    // Date range filters
    public $dateFrom = '';
    public $dateTo = '';
    
    // Sorting
    public $sortField = 'payment_date';
    public $sortDirection = 'desc';
    
    // Statistics
    public $todayTotal = 0;
    public $monthTotal = 0;
    public $yearTotal = 0;
    public $overallTotal = 0;
    
    // Modal states
    public $showDeleteModal = false;
    public $showViewModal = false;
    public $selectedPayment = null;
    
    // Bulk actions
    public $selectedPayments = [];
    public $selectAll = false;
    public $showBulkActionModal = false;
    public $bulkAction = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'reference' => ['except' => ''],
        'mpesaCode' => ['except' => ''],
        'paidBy' => ['except' => ''],
        'status' => ['except' => ''],
        'paymentMethod' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortField' => ['except' => 'payment_date'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->calculateStatistics();
    }

    public function updated($property)
    {
        // Reset pagination when filters change
        if (in_array($property, [
            'search', 'reference', 'mpesaCode', 'paidBy', 
            'status', 'paymentMethod', 'dateFrom', 'dateTo'
        ])) {
            $this->resetPage();
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPayments = $this->getFilteredQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedPayments = [];
        }
    }

    public function calculateStatistics()
    {
        $this->todayTotal = Payment::whereDate('payment_date', today())
            ->where('status', 'completed')
            ->sum('amount');
            
        $this->monthTotal = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->where('status', 'completed')
            ->sum('amount');
            
        $this->yearTotal = Payment::whereYear('payment_date', now()->year)
            ->where('status', 'completed')
            ->sum('amount');
            
        $this->overallTotal = Payment::where('status', 'completed')
            ->sum('amount');
    }

    protected function getFilteredQuery()
    {
        return Payment::query()
            ->with(['parcel', 'paidBy', 'mpesaTransaction'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference_number', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhereHas('parcel', function ($pq) {
                          $pq->where('parcel_id', 'like', '%' . $this->search . '%')
                             ->orWhere('sender_name', 'like', '%' . $this->search . '%')
                             ->orWhere('receiver_name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->reference, function ($query) {
                $query->where('reference_number', 'like', '%' . $this->reference . '%');
            })
            ->when($this->mpesaCode, function ($query) {
                $query->whereHas('mpesaTransaction', function ($q) {
                    $q->where('mpesa_receipt_number', 'like', '%' . $this->mpesaCode . '%');
                })->orWhere('reference_number', 'like', '%' . $this->mpesaCode . '%');
            })
            ->when($this->paidBy, function ($query) {
                $query->whereHas('paidBy', function ($q) {
                    $q->where('name', 'like', '%' . $this->paidBy . '%')
                      ->orWhere('email', 'like', '%' . $this->paidBy . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->paymentMethod, function ($query) {
                $query->where('payment_method', $this->paymentMethod);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('payment_date', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('payment_date', '<=', $this->dateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function viewPayment($id)
    {
        $this->selectedPayment = Payment::with(['parcel', 'paidBy', 'mpesaTransaction'])->find($id);
        $this->showViewModal = true;
    }

    public function confirmDelete($id)
    {
        $this->selectedPayment = Payment::find($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->selectedPayment) {
            $this->selectedPayment->delete();
            $this->showDeleteModal = false;
            $this->selectedPayment = null;
            $this->calculateStatistics();
            session()->flash('success', 'Payment deleted successfully.');
        }
    }

    public function openBulkActionModal($action)
    {
        if (count($this->selectedPayments) === 0) {
            session()->flash('error', 'Please select at least one payment.');
            return;
        }
        
        $this->bulkAction = $action;
        $this->showBulkActionModal = true;
    }

    public function executeBulkAction()
    {
        $payments = Payment::whereIn('id', $this->selectedPayments)->get();
        
        switch ($this->bulkAction) {
            case 'refund':
                foreach ($payments as $payment) {
                    $payment->update([
                        'status' => 'refunded',
                        'notes' => 'Bulk refund processed'
                    ]);
                }
                session()->flash('success', count($this->selectedPayments) . ' payments refunded successfully.');
                break;
                
            case 'complete':
                foreach ($payments as $payment) {
                    $payment->update([
                        'status' => 'completed',
                        'payment_date' => now(),
                        'notes' => 'Bulk completion processed'
                    ]);
                }
                session()->flash('success', count($this->selectedPayments) . ' payments completed successfully.');
                break;
                
            case 'delete':
                Payment::whereIn('id', $this->selectedPayments)->delete();
                session()->flash('success', count($this->selectedPayments) . ' payments deleted successfully.');
                break;
        }
        
        $this->selectedPayments = [];
        $this->selectAll = false;
        $this->showBulkActionModal = false;
        $this->calculateStatistics();
    }

    public function resetFilters()
    {
        $this->reset([
            'search', 'reference', 'mpesaCode', 'paidBy', 
            'status', 'paymentMethod', 'dateFrom', 'dateTo'
        ]);
        $this->resetPage();
    }

    public function export($format)
    {
        // Implement export functionality
        session()->flash('info', 'Export functionality coming soon.');
    }

    public function render()
    {
        $payments = $this->getFilteredQuery()->paginate(10);
        
        $statuses = [
            '' => 'All Statuses',
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'refunded' => 'Refunded'
        ];
        
        $paymentMethods = [
            '' => 'All Methods',
            'cash' => 'Cash',
            'mpesa' => 'M-Pesa',
            'card' => 'Card',
            'bank_transfer' => 'Bank Transfer',
            'wallet' => 'Wallet'
        ];

        return view('livewire.admin.payments.payments', [
            'payments' => $payments,
            'statuses' => $statuses,
            'paymentMethods' => $paymentMethods,
        ]);
    }
}