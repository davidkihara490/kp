<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;
    public string $search = '';
    protected $paginationTheme = 'bootstrap';

    public $deleteId;
    public $showDeleteModal = false;

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
        } catch (\Throwable $th) {
            return redirect()->route('admin.users.index')->with(['error', 'Error deleting user :' . $th->getMessage()]);
        }
    }
    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('first_name', 'ASC')
            ->paginate(10);
        return view('livewire.admin.users.users', [
            'users' => $users
        ]);
    }
}
