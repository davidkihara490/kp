<?php

namespace App\Livewire\Admin\Settings\Pricing;

use App\Models\Pricing;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Pricings extends Component
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
        $pricing = Pricing::findOrFail($this->deleteId);
        try {
            DB::beginTransaction();
            $pricing->delete();
            DB::commit();
            $this->showDeleteModal = false;
            session()->flash('success', 'Pricing deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('admin.pricings.index')->with(['error', 'Error deleting pricing :' . $th->getMessage()]);
        }
    }

    public function render()
    {
        $pricings = Pricing::query()
            // ->when($this->search, function ($query) {
            //     $query->where('name', 'like', '%' . $this->search . '%');
            // })
            // ->orderBy('n')
            ->paginate(10);

        return view('livewire.admin.settings.pricing.pricings', [
            'pricings' => $pricings
        ]);
    }
}
