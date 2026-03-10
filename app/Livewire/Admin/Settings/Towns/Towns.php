<?php

namespace App\Livewire\Admin\Settings\Towns;

use App\Models\Town;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Towns extends Component
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
        $town = Town::findOrFail($this->deleteId);
        try {
            DB::beginTransaction();
            $town->delete();
            DB::commit();
            $this->showDeleteModal = false;
            session()->flash('success', 'Town deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->company_route('towns.index')->with(['error', 'Error deleting town :' . $th->getMessage()]);
        }
    }

    public function render()
    {
        $towns = Town::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);
        return view('livewire.admin.settings.towns.towns', [
            'towns' => $towns
        ]);
    }
}
