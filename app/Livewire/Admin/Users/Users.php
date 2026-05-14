<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;
    
    public string $search = '';
    public string $userType = '';
    public string $status = '';
    public string $role = '';
    public string $sortField = 'first_name';
    public string $sortDirection = 'ASC';
    public $perPage = 10;
    
    public $deleteId;
    public $showDeleteModal = false;
    
    protected $paginationTheme = 'bootstrap';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'userType' => ['except' => ''],
        'status' => ['except' => ''],
        'role' => ['except' => ''],
        'sortField' => ['except' => 'first_name'],
        'sortDirection' => ['except' => 'ASC'],
        'perPage' => ['except' => 10],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingUserType()
    {
        $this->resetPage();
    }
    
    public function updatingStatus()
    {
        $this->resetPage();
    }
    
    public function updatingRole()
    {
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'ASC' ? 'DESC' : 'ASC';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'ASC';
        }
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->userType = '';
        $this->status = '';
        $this->role = '';
        $this->sortField = 'first_name';
        $this->sortDirection = 'ASC';
        $this->perPage = 10;
        $this->resetPage();
    }
    
    public function confirm($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }
    
    public function delete()
    {
        $user = User::findOrFail($this->deleteId);
        try {
            $user->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'User deleted successfully');
            $this->resetPage();
        } catch (\Throwable $th) {
            session()->flash('error', 'Error deleting user: ' . $th->getMessage());
        }
    }
    
    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('second_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone_number', 'like', '%' . $this->search . '%')
                      ->orWhere('user_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->userType, function ($query) {
                $query->where('user_type', $this->userType);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->role, function ($query) {
                $query->role($this->role);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        // Get filter options
        $userTypes = User::distinct()->pluck('user_type')->filter()->values();
        $statuses = ['active', 'inactive', 'pending', 'suspended'];
        $roles = \Spatie\Permission\Models\Role::pluck('name');
        
        return view('livewire.admin.users.users', [
            'users' => $users,
            'userTypes' => $userTypes,
            'statuses' => $statuses,
            'roles' => $roles,
        ]);
    }
}