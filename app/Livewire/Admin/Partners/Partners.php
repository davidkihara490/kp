<?php

namespace App\Livewire\Admin\Partners;

use App\Models\County;
use App\Models\Partner;
use App\Models\Town;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Partners extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Search and Filters
    public string $search = '';
    public string $partner_type = '';
    public string $status = '';
    public string $verification_status = '';
    public string $county = '';
    public string $town = '';
    public string $registration_date_from = '';
    public string $registration_date_to = '';

    // Sorting
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    // Bulk Actions
    public array $selectedPartners = [];
    public bool $selectAll = false;

    // Modal States
    public $deleteId;
    public bool $showDeleteModal = false;
    public bool $showBulkActionModal = false;
    public string $bulkAction = '';

    // Available filters
    public $partnerTypes = [];
    public $statuses = [];
    public $verificationStatuses = [];
    public $counties = [];
    public $towns = [];

    protected $listeners = ['refreshPartners' => '$refresh'];

    public function mount()
    {
        $this->initializeFilterOptions();
    }

    private function initializeFilterOptions()
    {
        $this->partnerTypes = [
            '' => 'All Types',
            'transport' => 'Transport Partners',
            'station' => 'Pick-up/Drop-off Partners',
            'pha' => 'Parcel Handling Assistants',
            'driver' => 'Drivers',
        ];

        $this->statuses = [
            '' => 'All Statuses',
            'pending' => 'Pending',
            'active' => 'Active',
            'suspended' => 'Suspended',
            'inactive' => 'Inactive',
        ];

        $this->verificationStatuses = [
            '' => 'All Verification',
            'pending' => 'Pending Verification',
            'verified' => 'Verified',
            'rejected' => 'Rejected',
        ];

        // Load dynamic data (you might want to cache these)
        $this->counties = County::orderBy('name')->pluck('name', 'id')->toArray();
        $this->towns = Town::orderBy('name')->pluck('name', 'id')->toArray();
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

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedPartners = $this->getPartnersQuery()->pluck('id')->toArray();
        } else {
            $this->selectedPartners = [];
        }
    }

    public function updatedSelectedPartners()
    {
        $this->selectAll = count($this->selectedPartners) === $this->getPartnersQuery()->count();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $partner = Partner::findOrFail($this->deleteId);

        try {
            if ($partner->owner) {
                $partner->owner->delete();
            }

            if ($partner->inCharge) {
                $partner->inCharge->delete();
            }


            $partner->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'Partner deleted successfully.');
            $this->dispatch('refreshPartners');
        } catch (\Throwable $th) {
            session()->flash('error', 'Error deleting partner: ' . $th->getMessage());
        }
    }

    public function openBulkActionModal($action)
    {
        if (empty($this->selectedPartners)) {
            session()->flash('warning', 'Please select at least one partner.');
            return;
        }

        $this->bulkAction = $action;
        $this->showBulkActionModal = true;
    }

    public function executeBulkAction()
    {
        try {
            $partnerIds = $this->selectedPartners;

            switch ($this->bulkAction) {
                case 'activate':
                    Partner::whereIn('id', $partnerIds)->update(['status' => 'active']);
                    User::whereIn('id', function ($query) use ($partnerIds) {
                        $query->select('owner_id')
                            ->from('partners')
                            ->whereIn('id', $partnerIds);
                    })->update(['status' => 'active']);
                    $message = 'Selected partners activated successfully.';
                    break;

                case 'suspend':
                    Partner::whereIn('id', $partnerIds)->update(['status' => 'suspended']);
                    User::whereIn('id', function ($query) use ($partnerIds) {
                        $query->select('owner_id')
                            ->from('partners')
                            ->whereIn('id', $partnerIds);
                    })->update(['status' => 'suspended']);
                    $message = 'Selected partners suspended successfully.';
                    break;

                case 'deactivate':
                    Partner::whereIn('id', $partnerIds)->update(['status' => 'inactive']);
                    User::whereIn('id', function ($query) use ($partnerIds) {
                        $query->select('owner_id')
                            ->from('partners')
                            ->whereIn('id', $partnerIds);
                    })->update(['status' => 'inactive']);
                    $message = 'Selected partners deactivated successfully.';
                    break;

                case 'verify':
                    Partner::whereIn('id', $partnerIds)->update(['verification_status' => 'verified']);
                    $message = 'Selected partners verified successfully.';
                    break;

                case 'delete':
                    // Bulk delete with relationships
                    Partner::whereIn('id', $partnerIds)->each(function ($partner) {
                        if ($partner->user) {
                            $partner->user->delete();
                        }
                        $partner->delete();
                    });
                    $message = 'Selected partners deleted successfully.';
                    break;

                default:
                    throw new \Exception('Invalid bulk action.');
            }

            $this->showBulkActionModal = false;
            $this->selectedPartners = [];
            $this->selectAll = false;

            session()->flash('success', $message);
            $this->dispatch('refreshPartners');
        } catch (\Throwable $th) {
            session()->flash('error', 'Error executing bulk action: ' . $th->getMessage());
        }
    }

    public function exportPartners($format = 'csv')
    {
        $partners = $this->getPartnersQuery()->get();

        // Implement export logic here
        // You can use Laravel Excel or custom CSV generation
        return response()->streamDownload(function () use ($partners, $format) {
            echo $this->generateExportData($partners, $format);
        }, 'partners-' . now()->format('Y-m-d') . '.' . $format);
    }

    private function generateExportData($partners, $format)
    {
        // Implement export data generation
        // This is a simplified example for CSV
        $headers = ['ID', 'Name', 'Type', 'Email', 'Phone', 'Status', 'Verification', 'Registration Date'];
        $data = $partners->map(function ($partner) {
            return [
                $partner->id,
                $partner->getFullName(),
                $partner->partner_type,
                $partner->email,
                $partner->phone_number,
                $partner->status,
                $partner->verification_status,
                $partner->created_at->format('Y-m-d'),
            ];
        });

        if ($format === 'csv') {
            $output = fopen('php://output', 'w');
            fputcsv($output, $headers);
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
        }

        return ob_get_clean();
    }

    public function resetFilters()
    {
        $this->reset([
            'search',
            'partner_type',
            'status',
            'verification_status',
            'county',
            'town',
            'registration_date_from',
            'registration_date_to'
        ]);
        $this->resetPage();
    }

    private function getPartnersQuery()
    {
        return Partner::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('company_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->partner_type, function ($query) {
                $query->where('partner_type', $this->partner_type);
            })
            ->when($this->status, function ($query) {
                $query->where('verification_status', $this->status);
            })
            ->when($this->verification_status, function ($query) {
                $query->where('verification_status', $this->verification_status);
            })
            // ->when($this->county, function ($query) {
            //     $query->whereHas('towns', function ($q) {
            //         $q->whereHas('subcounty.county', function ($q2) {
            //             $q2->where('id', $this->county);
            //         });
            //     });
            // })
            ->when($this->town, function ($query) {
                $query->whereHas('towns', function ($q) {
                    $q->where('id', $this->town);
                });
            })
            ->when($this->registration_date_from, function ($query) {
                $query->whereDate('created_at', '>=', $this->registration_date_from);
            })
            ->when($this->registration_date_to, function ($query) {
                $query->whereDate('created_at', '<=', $this->registration_date_to);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->with(['towns' => function ($q) {
                $q->with('town.subCounty');
                // $q->with('subCounty.county');
            }]);
    }

    public function render()
    {
        $partners = $this->getPartnersQuery()->paginate(20);

        return view('livewire.admin.partners.partners', [
            'partners' => $partners,
            'totalPartners' => Partner::count(),
            'activePartners' => Partner::where('verification_status', 'verified')->count(),
            'pendingPartners' => Partner::where('verification_status', 'pending')->count(),
        ]);
    }
}
