<?php

namespace App\Livewire\Admin\Settings\Zones;

use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Zones extends Component
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
        $zone = Zone::findOrFail($this->deleteId);
        try {
            DB::beginTransaction();
            $zone->delete();
            DB::commit();
            $this->showDeleteModal = false;
            session()->flash('success', 'Zone deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('admin.zones.index')->with(['error', 'Error deleting zone :' . $th->getMessage()]);
        }
    }

    public function render()
    {
        $zones = Zone::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->paginate(10);


        return view('livewire.admin.settings.zones.zones', [
            'zones' => $zones
        ]);
    }
}
