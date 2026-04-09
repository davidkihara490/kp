<?php

namespace App\Livewire\Partners\ParcelHandlingAssistants;

use App\Models\ParcelHandlingAssistant;
use App\Models\PickUpAndDropOffPoint;
use App\Models\User;
use App\Models\AssistantEmployment;
use App\Models\Parcel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ViewParcelHandlingAssistant extends Component
{
    // Stats
    public $activeStationsCount = 0;
    public $parcelsToday = 0;
    public $parcelsThisWeek = 0;
    public $allParcels = 0;
    public $parcelsThisMonth = 0;
    public $lastActivity = null;
    public $recentActivities = [];

    // Modals
    public $showAssignStationModal = false;
    public $showSuspendModal = false;
    public $showCreateAccountModal = false;

    // Form fields
    public $selectedStation = '';
    public $sendSuspensionNotification = false;
    public $sendWelcomeEmail = true;
    public $generatedPassword = '';

    // Data
    public $stations = [];

    public ParcelHandlingAssistant $parcelHandlingAssistant;

    public function mount($id)
    {
        $this->parcelHandlingAssistant = ParcelHandlingAssistant::findOrFail($id);

        $query = Parcel::where('pha_id', $this->parcelHandlingAssistant->id);

        $this->allParcels = $query->count();
        $this->parcelsToday = (clone $query)->whereDate('date', Carbon::today())->count();
        $this->parcelsThisWeek = (clone $query)
            ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
        $this->parcelsThisMonth = (clone $query)
            ->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Status change activities
        if ($this->parcelHandlingAssistant->status === 'suspended') {
            $activities[] = [
                'icon' => 'bi-ban',
                'text' => 'Account suspended',
                'time' => $this->parcelHandlingAssistant->updated_at->diffForHumans(),
                'color' => 'danger'
            ];
        } elseif ($this->parcelHandlingAssistant->status === 'active') {
            $activities[] = [
                'icon' => 'bi-check-circle',
                'text' => 'Account activated',
                'time' => $this->parcelHandlingAssistant->updated_at->diffForHumans(),
                'color' => 'success'
            ];
        }

        // User account creation
        if ($this->parcelHandlingAssistant->user) {
            $activities[] = [
                'icon' => 'bi-person-check',
                'text' => 'User account created',
                'time' => $this->parcelHandlingAssistant->user->created_at->diffForHumans(),
                'color' => 'success'
            ];
        }

        // Sort by time (most recent first)
        usort($activities, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 5); // Return only 5 most recent
    }

    public function getStatusBadgeClass()
    {
        return 'status-' . $this->parcelHandlingAssistant->status;
    }

    public function getStatusIcon()
    {
        return match ($this->parcelHandlingAssistant->status) {
            'active' => 'bi-check-circle',
            'inactive' => 'bi-pause-circle',
            'suspended' => 'bi-ban',
            'pending' => 'bi-clock',
            default => 'bi-question-circle'
        };
    }

    public function showAssignStation()
    {
        $this->selectedStation = '';
        $this->showAssignStationModal = true;
    }

    public function assignStation()
    {
        $this->validate(['selectedStation' => 'required|exists:pick_up_and_drop_off_points,id']);

        try {
            // Check if already employed at this station
            $existingAssignment = $this->parcelHandlingAssistant->assignments()
                ->where('pick_up_and_drop_off_point_id', $this->selectedStation)
                ->first();

            if ($existingAssignment) {
                // Update existing employment to active
                $existingAssignment->update(['status' => 'active']);
                $message = "Assistant already assigned to this point. Employment reactivated.";
            } else {
                // Create new employment
                $this->parcelHandlingAssistant->assignments()->create([
                    'pick_up_and_drop_off_point_id' => $this->selectedStation,
                    'status' => 'active',
                    'partner_id' => $this->parcelHandlingAssistant->partner->id,
                    'from' => Carbon::now(),
                    'assigned_by' => Auth::guard('partner')->user()->id,
                ]);
                $message = "Assistant assigned to station successfully.";
            }

            // Reload data
            $this->loadAssistant();
            $this->loadStats();
            $this->showAssignStationModal = false;

            session()->flash('success', $message);
        } catch (\Exception $e) {
            dd($e->getMessage());
            session()->flash('error', 'Failed to assign station: ' . $e->getMessage());
        }
    }

    public function confirmSuspend()
    {
        $this->sendSuspensionNotification = false;
        $this->showSuspendModal = true;
    }

    public function suspendAssistant()
    {
        try {
            DB::transaction(function () {
                // Update parcelHandlingAssistant status
                $this->parcelHandlingAssistant->update(['status' => 'suspended']);

                // Update user status if exists
                if ($this->parcelHandlingAssistant->user) {
                    $this->parcelHandlingAssistant->user->update(['status' => 'suspended']);
                }

                // Suspend all active employments
                $this->parcelHandlingAssistant->assignments()->where('status', 'active')->update(['status' => 'suspended']);

                // Send notification if requested
                if ($this->sendSuspensionNotification && $this->parcelHandlingAssistant->email) {
                    // \Mail::to($this->parcelHandlingAssistant->email)->send(new SuspensionEmail($this->parcelHandlingAssistant));
                }
            });

            // Reload data
            $this->loadAssistant();
            $this->loadStats();
            $this->showSuspendModal = false;

            session()->flash('warning', 'Assistant suspended successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to suspend assistant: ' . $e->getMessage());
        }
    }

    public function activateAssistant()
    {
        try {
            DB::transaction(function () {
                $this->parcelHandlingAssistant->update(['status' => 'active']);

                if ($this->parcelHandlingAssistant->user) {
                    $this->parcelHandlingAssistant->user->update(['status' => 'active']);
                }
            });

            $this->loadAssistant();
            session()->flash('success', 'Assistant activated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to activate assistant: ' . $e->getMessage());
        }
    }

    public function deleteAssistant()
    {
        try {
            $name = $this->parcelHandlingAssistant->full_name;
            $this->parcelHandlingAssistant->delete();
            session()->flash('success', "Assistant '{$name}' deleted successfully!");
            $this->dispatch('assistantDeleted', $this->assistant_id);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete assistant: ' . $e->getMessage());
        }
    }

    private function generateRandomPassword($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        return substr(str_shuffle($chars), 0, $length);
    }

    public function render()
    {
        return view('livewire.partners.parcel-handling-assistants.view-parcel-handling-assistant');
    }
}
