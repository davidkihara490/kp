<?php

namespace App\Livewire\Admin\Settings\Faqs;

use App\Models\FAQ;
use Livewire\Component;
use Livewire\WithPagination;

class Faqs extends Component
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
        $faq = FAQ::findOrFail($this->deleteId);
        try {
            $faq->delete();
            $this->showDeleteModal = false;
            session()->flash('success', 'FAQ deleted successfully');
        } catch (\Throwable $th) {
            return redirect()->route('admin.faqs.index')->with(['error', 'Error deleting faq :' . $th->getMessage()]);
        }
    }
    public function render()
    {
        $faqs = FAQ::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('id')
            ->paginate(10);
        return view('livewire.admin.settings.faqs.faqs', [
            'faqs' => $faqs
        ]);
    }
}
