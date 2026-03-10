<?php

namespace App\Livewire\Partners\ParcelHandlingAssistants;

use App\Models\ParcelHandlingAssistant;
use App\Models\PickUpAndDropOffPoint;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ParcelHandlingAssistants extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $statusFilter = '';
    public $stationFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showDeleteModal = false;
    public $assistantToDelete = null;
    public $showBulkActions = false;
    public $selectedAssistants = [];
    public $selectAll = false;
    public $showEmploymentModal = false;
    public $selectedAssistantForEmployment = null;
    public $selectedStation = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'stationFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function render()
    {

        $partner = Auth::guard('partner')->user()->partner;
        $query = ParcelHandlingAssistant::where('partner_id', $partner->id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                        ->orWhere('id_number', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($q) {
                            $q->where('first_name', 'like', '%' . $this->search . '%')
                                ->orWhere('last_name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            });
           
        $assistants = $query->orderBy($this->sortField, $this->sortDirection)->paginate(15);

        return view('livewire.partners.parcel-handling-assistants.parcel-handling-assistants', [
            'assistants' => $assistants,
            'stations' => PickUpAndDropOffPoint::all(),
            'statuses' => [
                '' => 'All Status',
                'active' => 'Active',
                'inactive' => 'Inactive',
                'suspended' => 'Suspended',
                'pending' => 'Pending'
            ],
            'totalAssistants' => ParcelHandlingAssistant::count(),
            'activeAssistants' => ParcelHandlingAssistant::where('status', 'active')->count(),
            'inactiveAssistants' => ParcelHandlingAssistant::where('status', 'inactive')->count(),
            'suspendedAssistants' => ParcelHandlingAssistant::where('status', 'suspended')->count(),
            'pendingAssistants' => ParcelHandlingAssistant::where('status', 'pending')->count(),
        ]);
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

    public function confirmDelete($assistantId)
    {
        $this->assistantToDelete = ParcelHandlingAssistant::find($assistantId);
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->assistantToDelete) {
            $name = $this->assistantToDelete->full_name;
            $this->assistantToDelete->user()->delete();
            $this->assistantToDelete->delete();

            session()->flash('success', "Assistant '{$name}' deleted successfully.");
            $this->showDeleteModal = false;
            $this->assistantToDelete = null;
        }
    }

    public function toggleStatus($assistantId)
    {
        $assistant = ParcelHandlingAssistant::find($assistantId);
        if ($assistant) {
            $newStatus = $assistant->status === 'active' ? 'inactive' : 'active';
            $assistant->update(['status' => $newStatus]);

            // Also update user status if exists
            if ($assistant->user) {
                $assistant->user->update(['status' => $newStatus]);
            }

            session()->flash('success', "Assistant status updated to " . ucfirst($newStatus));
        }
    }

    public function suspendAssistant($assistantId)
    {
        $assistant = ParcelHandlingAssistant::find($assistantId);
        if ($assistant) {
            $assistant->update(['status' => 'suspended']);

            if ($assistant->user) {
                $assistant->user->update(['status' => 'suspended']);
            }

            // Suspend all active employments
            $assistant->employments()->where('status', 'active')->update(['status' => 'suspended']);

            session()->flash('warning', "Assistant '{$assistant->full_name}' has been suspended.");
        }
    }

    public function activateAssistant($assistantId)
    {
        $assistant = ParcelHandlingAssistant::find($assistantId);
        if ($assistant) {
            $assistant->update(['status' => 'active']);

            if ($assistant->user) {
                $assistant->user->update(['status' => 'active']);
            }

            session()->flash('success', "Assistant '{$assistant->full_name}' has been activated.");
        }
    }

    public function showAssignStation($assistantId)
    {
        $this->selectedAssistantForEmployment = ParcelHandlingAssistant::find($assistantId);
        $this->selectedStation = '';
        $this->showEmploymentModal = true;
    }

    public function assignStation()
    {
        $this->validate([
            'selectedStation' => 'required|exists:pick_up_and_drop_off_points,id',
        ]);

        if ($this->selectedAssistantForEmployment) {
            // Check if already employed at this station
            $existingEmployment = $this->selectedAssistantForEmployment->employments()
                ->where('partner_id', $this->selectedStation)
                ->first();

            if ($existingEmployment) {
                // Update existing employment to active
                $existingEmployment->update(['status' => 'active']);
                $message = "Assistant already assigned to this station. Employment reactivated.";
            } else {
                // Create new employment
                $this->selectedAssistantForEmployment->employments()->create([
                    'partner_id' => $this->selectedStation,
                    'status' => 'active',
                    'created_by' => Auth::id(),
                ]);
                $message = "Assistant assigned to station successfully.";
            }

            session()->flash('success', $message);
            $this->showEmploymentModal = false;
            $this->selectedAssistantForEmployment = null;
            $this->selectedStation = '';
        }
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->stationFilter = '';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->selectedAssistants = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $query = ParcelHandlingAssistant::query();

            if ($this->statusFilter) {
                $query->where('status', $this->statusFilter);
            }

            $this->selectedAssistants = $query->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedAssistants = [];
        }
        $this->showBulkActions = count($this->selectedAssistants) > 0;
    }

    public function updatedSelectedAssistants()
    {
        $this->selectAll = false;
        $this->showBulkActions = count($this->selectedAssistants) > 0;
    }

    public function bulkDelete()
    {
        if (count($this->selectedAssistants) > 0) {
            $assistants = ParcelHandlingAssistant::whereIn('id', $this->selectedAssistants)->get();

            foreach ($assistants as $assistant) {
                $assistant->delete();
            }

            session()->flash('success', count($this->selectedAssistants) . ' assistants deleted.');

            $this->selectedAssistants = [];
            $this->selectAll = false;
            $this->showBulkActions = false;
        }
    }

    public function bulkActivate()
    {
        if (count($this->selectedAssistants) > 0) {
            DB::transaction(function () {
                $assistants = ParcelHandlingAssistant::whereIn('id', $this->selectedAssistants)->get();

                foreach ($assistants as $assistant) {
                    $assistant->update(['status' => 'active']);
                    if ($assistant->user) {
                        $assistant->user->update(['status' => 'active']);
                    }
                }
            });

            session()->flash('success', count($this->selectedAssistants) . ' assistants activated.');

            $this->selectedAssistants = [];
            $this->selectAll = false;
            $this->showBulkActions = false;
        }
    }

    public function bulkSuspend()
    {
        if (count($this->selectedAssistants) > 0) {
            DB::transaction(function () {
                $assistants = ParcelHandlingAssistant::whereIn('id', $this->selectedAssistants)->get();

                foreach ($assistants as $assistant) {
                    $assistant->update(['status' => 'suspended']);
                    if ($assistant->user) {
                        $assistant->user->update(['status' => 'suspended']);
                    }
                    // Suspend all employments
                    $assistant->employments()->where('status', 'active')->update(['status' => 'suspended']);
                }
            });

            session()->flash('warning', count($this->selectedAssistants) . ' assistants suspended.');

            $this->selectedAssistants = [];
            $this->selectAll = false;
            $this->showBulkActions = false;
        }
    }

    public function getStatusBadge($status)
    {
        return match ($status) {
            'active' => ['color' => 'success', 'text' => 'Active', 'icon' => 'fa-check-circle'],
            'inactive' => ['color' => 'secondary', 'text' => 'Inactive', 'icon' => 'fa-times-circle'],
            'suspended' => ['color' => 'danger', 'text' => 'Suspended', 'icon' => 'fa-ban'],
            'pending' => ['color' => 'warning', 'text' => 'Pending', 'icon' => 'fa-clock'],
            default => ['color' => 'info', 'text' => ucfirst($status), 'icon' => 'fa-question-circle']
        };
    }
}
