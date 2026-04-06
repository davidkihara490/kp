<?php

namespace App\Livewire\Admin\Settings\RolesAndPermissions;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RolesAndPermissions extends Component
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
        $role = Role::findOrFail($this->deleteId);
        try {
            $role->users()->detach();
            $role->syncPermissions([]);
            $role->delete();
            $$this->showDeleteModal = false;
            session()->flash('success', 'Role deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->route('admin.roles-and-permissions.index')->with(['error', 'Error deleting role :' . $th->getMessage()]);
        }
    }
    public function render()
    {
        $roles = Role::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id')
            ->paginate(10);
        return view('livewire.admin.settings.roles-and-permissions.roles-and-permissions', [
            'roles' => $roles
        ]);
    }
}
