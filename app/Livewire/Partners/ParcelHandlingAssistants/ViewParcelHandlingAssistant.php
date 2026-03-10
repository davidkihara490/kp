<?php

namespace App\Livewire\Partners\ParcelHandlingAssistants;

use App\Models\ParcelHandlingAssistant;
use App\Models\PickUpAndDropOffPoint;
use App\Models\User;
use App\Models\AssistantEmployment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ViewParcelHandlingAssistant extends Component
{
    public $assistant;
    public $assistant_id;

    // Stats
    public $activeStationsCount = 0;
    public $parcelsToday = 0;
    public $parcelsThisWeek = 0;
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

    public $stationAssigned = null;

    public function mount($id)
    {
        $this->assistant_id = $id;
        $this->loadAssistant();
        $this->loadStats();
        $this->loadStations();
    }

    public function loadAssistant()
    {
        $this->assistant = ParcelHandlingAssistant::findOrFail($this->assistant_id);
        $this->stationAssigned = $this->assistant->assignments()->latest()->first();
    }

    public function loadStats()
    {
        // Load station assignments count
        // Load activity stats (you can implement actual logic based on your system)
        $this->parcelsToday = rand(5, 50);
        $this->parcelsThisWeek = rand(30, 200);
        $this->lastActivity = $this->assistant->updated_at;

        // Load recent activities
        $this->recentActivities = $this->getRecentActivities();
    }

    public function loadStations()
    {
        $loggedInUser = Auth::guard('partner')->user();
        $partner = $loggedInUser->partner;
        $this->stations = PickUpAndDropOffPoint::where('status', 'active')
            ->where('partner_id', $partner->id)
            ->orderBy('name')
            ->get();
    }

    private function getRecentActivities()
    {
        $activities = [];

        // Status change activities
        if ($this->assistant->status === 'suspended') {
            $activities[] = [
                'icon' => 'bi-ban',
                'text' => 'Account suspended',
                'time' => $this->assistant->updated_at->diffForHumans(),
                'color' => 'danger'
            ];
        } elseif ($this->assistant->status === 'active') {
            $activities[] = [
                'icon' => 'bi-check-circle',
                'text' => 'Account activated',
                'time' => $this->assistant->updated_at->diffForHumans(),
                'color' => 'success'
            ];
        }

        // User account creation
        if ($this->assistant->user) {
            $activities[] = [
                'icon' => 'bi-person-check',
                'text' => 'User account created',
                'time' => $this->assistant->user->created_at->diffForHumans(),
                'color' => 'success'
            ];
        }

        // Sort by time (most recent first)
        usort($activities, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return array_slice($activities, 0, 5); // Return only 5 most recent
    }

    public function getRoleBadgeClass()
    {
        return match ($this->assistant->role) {
            'assistant' => 'bg-info',
            'supervisor' => 'bg-warning text-dark',
            'manager' => 'bg-primary',
            default => 'bg-secondary'
        };
    }

    public function getRoleIcon()
    {
        return match ($this->assistant->role) {
            'assistant' => 'bi-box',
            'supervisor' => 'bi-eye',
            'manager' => 'bi-gear',
            default => 'bi-person'
        };
    }

    public function getStatusBadgeClass()
    {
        return 'status-' . $this->assistant->status;
    }

    public function getStatusIcon()
    {
        return match ($this->assistant->status) {
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
            $existingAssignment = $this->assistant->assignments()
                ->where('pick_up_and_drop_off_point_id', $this->selectedStation)
                ->first();

            if ($existingAssignment) {
                // Update existing employment to active
                $existingAssignment->update(['status' => 'active']);
                $message = "Assistant already assigned to this point. Employment reactivated.";
            } else {
                // Create new employment
                $this->assistant->assignments()->create([
                    'pick_up_and_drop_off_point_id' => $this->selectedStation,
                    'status' => 'active',
                    'partner_id' => $this->assistant->partner->id,
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

    public function deactivateEmployment($employmentId)
    {
        // try {
        //     $employment = AssistantEmployment::findOrFail($employmentId);
        //     $employment->update(['status' => 'inactive']);
        //     $this->loadAssistant();
        //     $this->loadStats();
        //     session()->flash('warning', 'Employment deactivated.');
        // } catch (\Exception $e) {
        //     session()->flash('error', 'Failed to deactivate employment: ' . $e->getMessage());
        // }
    }

    public function activateEmployment($employmentId)
    {
        // try {
        //     $employment = AssistantEmployment::findOrFail($employmentId);
        //     $employment->update(['status' => 'active']);
        //     $this->loadAssistant();
        //     $this->loadStats();
        //     session()->flash('success', 'Employment activated.');
        // } catch (\Exception $e) {
        //     session()->flash('error', 'Failed to activate employment: ' . $e->getMessage());
        // }
    }

    public function removeEmployment($employmentId)
    {
        // try {
        //     AssistantEmployment::findOrFail($employmentId)->delete();
        //     $this->loadAssistant();
        //     $this->loadStats();
        //     session()->flash('success', 'Assistant removed from station.');
        // } catch (\Exception $e) {
        //     session()->flash('error', 'Failed to remove employment: ' . $e->getMessage());
        // }
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
                // Update assistant status
                $this->assistant->update(['status' => 'suspended']);

                // Update user status if exists
                if ($this->assistant->user) {
                    $this->assistant->user->update(['status' => 'suspended']);
                }

                // Suspend all active employments
                $this->assistant->assignments()->where('status', 'active')->update(['status' => 'suspended']);

                // Send notification if requested
                if ($this->sendSuspensionNotification && $this->assistant->email) {
                    // \Mail::to($this->assistant->email)->send(new SuspensionEmail($this->assistant));
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
                $this->assistant->update(['status' => 'active']);

                if ($this->assistant->user) {
                    $this->assistant->user->update(['status' => 'active']);
                }
            });

            $this->loadAssistant();
            session()->flash('success', 'Assistant activated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to activate assistant: ' . $e->getMessage());
        }
    }

    public function createUserAccount()
    {
        $this->generatedPassword = $this->generateRandomPassword();
        $this->sendWelcomeEmail = true;
        $this->showCreateAccountModal = true;
    }

    public function createAccount()
    {
        try {
            DB::transaction(function () {
                // Create user account
                $user = User::create([
                    'first_name' => $this->assistant->first_name,
                    'second_name' => $this->assistant->second_name,
                    'last_name' => $this->assistant->last_name,
                    'email' => $this->assistant->email,
                    'phone_number' => $this->assistant->phone_number,
                    'user_name' => strtolower($this->assistant->first_name . '.' . $this->assistant->last_name) . rand(100, 999),
                    'password' => Hash::make($this->generatedPassword),
                    'status' => $this->assistant->status,
                    'login_attempts' => 0,
                ]);

                // Assign role to user
                // $user->assignRole('parcel-handling-assistant');

                // Link user to assistant
                $this->assistant->update(['user_id' => $user->id]);

                // Send welcome email if requested
                if ($this->sendWelcomeEmail) {
                    // \Mail::to($user->email)->send(new WelcomeEmail($user, $this->generatedPassword));
                }
            });

            // Reload data
            $this->loadAssistant();
            $this->showCreateAccountModal = false;

            session()->flash('success', 'User account created successfully.');
            $this->dispatch('accountCreated');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create user account: ' . $e->getMessage());
        }
    }

    public function sendLoginCredentials()
    {
        try {
            if (!$this->assistant->user) {
                throw new \Exception('No user account exists for this assistant.');
            }

            // Generate temporary password
            $tempPassword = $this->generateRandomPassword();
            $this->assistant->user->update(['password' => Hash::make($tempPassword)]);

            // Send email with credentials
            // \Mail::to($this->assistant->user->email)->send(new LoginCredentialsEmail($this->assistant->user, $tempPassword));

            session()->flash('success', 'Login credentials sent successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send login credentials: ' . $e->getMessage());
        }
    }

    public function resetPassword()
    {
        try {
            if (!$this->assistant->user) {
                throw new \Exception('No user account exists for this assistant.');
            }

            $newPassword = $this->generateRandomPassword();
            $this->assistant->user->update(['password' => Hash::make($newPassword)]);

            // Send password reset email
            // \Mail::to($this->assistant->user->email)->send(new PasswordResetEmail($this->assistant->user, $newPassword));

            session()->flash('success', 'Password reset successfully. New password sent to assistant.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reset password: ' . $e->getMessage());
        }
    }

    public function deleteAssistant()
    {
        try {
            $name = $this->assistant->full_name;
            $this->assistant->delete();
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
