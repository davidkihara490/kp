<?php

namespace App\Livewire\Admin\Payouts;

use App\Models\ParcelPayout;
use App\Models\Partner;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Payouts extends Component
{
    use WithPagination;

    // Search filters
    public $search = '';
    public $reference = '';
    public $partnerName = '';
    public $type = '';
    public $status = '';
    public $destinationType = '';
    
    // Date range filters
    public $dateFrom = '';
    public $dateTo = '';
    public $paidDateFrom = '';
    public $paidDateTo = '';
    
    // Amount range filters
    public $minAmount = '';
    public $maxAmount = '';
    
    // Sorting
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Statistics
    public $pendingTotal = 0;
    public $completedTotal = 0;
    public $cancelledTotal = 0;
    public $overallTotal = 0;
    public $pendingCount = 0;
    public $completedCount = 0;
    
    // Modal states
    public $showDeleteModal = false;
    public $showViewModal = false;
    public $showProcessModal = false;
    public $showBulkProcessModal = false;
    public $selectedPayout = null;
    public $processAmount = 0;
    public $processReference = '';
    public $processNotes = '';
    public $processDate = '';
    
    // Bulk actions
    public $selectedPayouts = [];
    public $selectAll = false;
    public $showBulkActionModal = false;
    public $bulkAction = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'reference' => ['except' => ''],
        'partnerName' => ['except' => ''],
        'type' => ['except' => ''],
        'status' => ['except' => ''],
        'destinationType' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'paidDateFrom' => ['except' => ''],
        'paidDateTo' => ['except' => ''],
        'minAmount' => ['except' => ''],
        'maxAmount' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->processDate = now()->format('Y-m-d');
        $this->calculateStatistics();
    }

    public function updated($property)
    {
        // Reset pagination when filters change
        if (in_array($property, [
            'search', 'reference', 'partnerName', 'type', 'status', 
            'destinationType', 'dateFrom', 'dateTo', 'paidDateFrom', 
            'paidDateTo', 'minAmount', 'maxAmount'
        ])) {
            $this->resetPage();
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPayouts = $this->getFilteredQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedPayouts = [];
        }
    }

    public function calculateStatistics()
    {
        $this->pendingTotal = ParcelPayout::where('status', 'pending')->sum('amount');
        $this->completedTotal = ParcelPayout::where('status', 'paid')->sum('amount');
        $this->cancelledTotal = ParcelPayout::where('status', 'cancelled')->sum('amount');
        $this->overallTotal = ParcelPayout::sum('amount');
        $this->pendingCount = ParcelPayout::where('status', 'pending')->count();
        $this->completedCount = ParcelPayout::where('status', 'paid')->count();
    }

    protected function getFilteredQuery()
    {
        return ParcelPayout::query()
            ->with(['parcel', 'partner', 'origin', 'destination'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('parcel', function ($pq) {
                        $pq->where('parcel_id', 'like', '%' . $this->search . '%')
                           ->orWhere('sender_name', 'like', '%' . $this->search . '%')
                           ->orWhere('receiver_name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('partner', function ($ppq) {
                        $ppq->where('company_name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->reference, function ($query) {
                $query->whereHas('parcel', function ($q) {
                    $q->where('parcel_id', 'like', '%' . $this->reference . '%');
                });
            })
            ->when($this->partnerName, function ($query) {
                $query->whereHas('partner', function ($q) {
                    $q->where('company_name', 'like', '%' . $this->partnerName . '%')
                      ->orWhere('contact_person', 'like', '%' . $this->partnerName . '%');
                });
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->destinationType, function ($query) {
                $query->where('destination', $this->destinationType);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->when($this->paidDateFrom, function ($query) {
                $query->whereDate('paid_out_on', '>=', $this->paidDateFrom);
            })
            ->when($this->paidDateTo, function ($query) {
                $query->whereDate('paid_out_on', '<=', $this->paidDateTo);
            })
            ->when($this->minAmount, function ($query) {
                $query->where('amount', '>=', $this->minAmount);
            })
            ->when($this->maxAmount, function ($query) {
                $query->where('amount', '<=', $this->maxAmount);
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

    public function viewPayout($id)
    {
        $this->selectedPayout = ParcelPayout::with(['parcel', 'partner', 'origin', 'destination'])->find($id);
        $this->showViewModal = true;
    }

    public function processPayout($id)
    {
        $this->selectedPayout = ParcelPayout::find($id);
        $this->processAmount = $this->selectedPayout->amount;
        $this->processReference = '';
        $this->processNotes = '';
        $this->processDate = now()->format('Y-m-d');
        $this->showProcessModal = true;
    }

    public function confirmProcess()
    {
        $this->validate([
            'processAmount' => 'required|numeric|min:0',
            'processDate' => 'required|date',
            'processReference' => 'nullable|string|max:255',
            'processNotes' => 'nullable|string|max:500',
        ]);

        if ($this->selectedPayout) {
            $this->selectedPayout->update([
                'status' => 'paid',
                'paid_out_on' => $this->processDate,
                'notes' => $this->processNotes
            ]);

            $this->showProcessModal = false;
            $this->selectedPayout = null;
            $this->calculateStatistics();
            session()->flash('success', 'Payout processed successfully.');
        }
    }

    public function cancelPayout($id)
    {
        $payout = ParcelPayout::find($id);
        if ($payout && $payout->status === 'pending') {
            $payout->update([
                'status' => 'cancelled',
                'cancelation_reason' => 'Cancelled by admin'
            ]);
            $this->calculateStatistics();
            session()->flash('success', 'Payout cancelled successfully.');
        }
    }

    public function confirmDelete($id)
    {
        $this->selectedPayout = ParcelPayout::find($id);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->selectedPayout) {
            $this->selectedPayout->delete();
            $this->showDeleteModal = false;
            $this->selectedPayout = null;
            $this->calculateStatistics();
            session()->flash('success', 'Payout deleted successfully.');
        }
    }

    public function openBulkActionModal($action)
    {
        if (count($this->selectedPayouts) === 0) {
            session()->flash('error', 'Please select at least one payout.');
            return;
        }
        
        $this->bulkAction = $action;
        $this->showBulkActionModal = true;
    }

    public function executeBulkAction()
    {
        $payouts = ParcelPayout::whereIn('id', $this->selectedPayouts)->get();
        
        switch ($this->bulkAction) {
            case 'process':
                foreach ($payouts as $payout) {
                    if ($payout->status === 'pending') {
                        $payout->update([
                            'status' => 'paid',
                            'paid_out_on' => now(),
                            'notes' => 'Bulk payment processed'
                        ]);
                    }
                }
                session()->flash('success', count($this->selectedPayouts) . ' payouts processed successfully.');
                break;
                
            case 'cancel':
                foreach ($payouts as $payout) {
                    if ($payout->status === 'pending') {
                        $payout->update([
                            'status' => 'cancelled',
                            'cancelation_reason' => 'Bulk cancellation by admin'
                        ]);
                    }
                }
                session()->flash('success', count($this->selectedPayouts) . ' payouts cancelled successfully.');
                break;
                
            case 'delete':
                ParcelPayout::whereIn('id', $this->selectedPayouts)->delete();
                session()->flash('success', count($this->selectedPayouts) . ' payouts deleted successfully.');
                break;
        }
        
        $this->selectedPayouts = [];
        $this->selectAll = false;
        $this->showBulkActionModal = false;
        $this->calculateStatistics();
    }

    public function resetFilters()
    {
        $this->reset([
            'search', 'reference', 'partnerName', 'type', 'status', 
            'destinationType', 'dateFrom', 'dateTo', 'paidDateFrom', 
            'paidDateTo', 'minAmount', 'maxAmount'
        ]);
        $this->resetPage();
    }

    public function export($format)
    {
        session()->flash('info', 'Export functionality coming soon.');
    }

    public function render()
    {
        $payouts = $this->getFilteredQuery()->paginate(15);
        
        $types = [
            '' => 'All Types',
            'pickup-dropoff' => 'Pickup & Dropoff',
            'transport' => 'Transport',
            'delivery' => 'Delivery',
        ];
        
        $statuses = [
            '' => 'All Statuses',
            'pending' => 'Pending',
            'paid' => 'Paid',
            'cancelled' => 'Cancelled',
        ];
        
        $destinationTypes = [
            '' => 'All Destinations',
            'partner' => 'Partner',
            'station' => 'Station',
            'driver' => 'Driver',
        ];

        return view('livewire.admin.payouts.payouts', [
            'payouts' => $payouts,
            'types' => $types,
            'statuses' => $statuses,
            'destinationTypes' => $destinationTypes,
        ]);
    }
}