<?php

namespace App\Livewire\Admin\Settings\Terms;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TermsAndCondition;

class TermsAndConditions extends Component
{
    use WithPagination;

    public $search = '';
    public $localeFilter = '';
    public $statusFilter = '';
    public $showDeleteModal = false;
    public $showVersionModal = false;
    public $selectedTermId;
    public $selectedTermTitle;
    public $versionHistory;

    protected $queryString = [
        'search' => ['except' => ''],
        'localeFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $term = TermsAndCondition::withTrashed()->find($id);
        $this->selectedTermId = $id;
        $this->selectedTermTitle = $term->title;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $term = TermsAndCondition::withTrashed()->find($this->selectedTermId);
        
        if ($term->trashed()) {
            $term->forceDelete();
            session()->flash('success', 'Terms permanently deleted.');
        } else {
            $term->delete();
            session()->flash('success', 'Terms moved to trash.');
        }

        $this->showDeleteModal = false;
        $this->selectedTermId = null;
        $this->selectedTermTitle = null;
    }

    public function activate($id)
    {
        $term = TermsAndCondition::find($id);
        $term->activate();
        session()->flash('success', 'Terms activated successfully.');
    }

    public function deactivate($id)
    {
        $term = TermsAndCondition::find($id);
        $term->update(['is_active' => false]);
        session()->flash('success', 'Terms deactivated successfully.');
    }

    public function restore($id)
    {
        TermsAndCondition::withTrashed()->find($id)->restore();
        session()->flash('success', 'Terms restored successfully.');
    }

    public function forceDelete($id)
    {
        TermsAndCondition::withTrashed()->find($id)->forceDelete();
        session()->flash('success', 'Terms permanently deleted.');
    }

    public function showVersionHistory($id)
    {
        $this->selectedTermId = $id;
        $term = TermsAndCondition::find($id);
        $this->selectedTermTitle = $term->title;
        
        $this->versionHistory = TermsAndCondition::where('title', $term->title)
            ->orWhere('version', $term->version)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $this->showVersionModal = true;
    }

    public function render()
    {
        $query = TermsAndCondition::query()
            ->withTrashed();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('version', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->localeFilter) {
            $query->where('locale', $this->localeFilter);
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        $terms = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.settings.terms.terms-and-conditions', [
            'terms' => $terms
        ]);
    }
}



// namespace App\Livewire\Admin\Settings\Terms;

// use App\Models\TermsAndCondition;
// use Livewire\Component;
// use Livewire\WithPagination;

// class TermsAndConditions extends Component
// {
//     use WithPagination;
//     public string $search = '';
//     protected $paginationTheme = 'bootstrap';

//     public $deleteId;
//     public $showDeleteModal = false;

//     public function confirm($id)
//     {
//         $this->deleteId = $id;
//         $this->showDeleteModal = true;
//     }
//     public function delete()
//     {
//         $termsAndConditions = TermsAndCondition::findOrFail($this->deleteId);
//         try {
//             $termsAndConditions->delete();
//             $this->showDeleteModal = false;
//             session()->flash('success', 'Terms deleted successfully');
//         } catch (\Throwable $th) {
//             return redirect()->route('admin.terms')->with(['error', 'Error deleting terms :' . $th->getMessage()]);
//         }
//     }

//     public function render()
//     {
//         $termsAndConditions = TermsAndCondition::query()
//             ->when($this->search, function ($query) {
//                 $query->where('title', 'like', '%' . $this->search . '%');
//             })
//             ->paginate(10);

//         return view('livewire.admin.settings.terms.terms-and-conditions', [
//             'termsAndConditions' => $termsAndConditions
//         ]);
//     }
// }