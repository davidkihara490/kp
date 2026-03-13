<?php

namespace App\Livewire\Admin\Settings\Terms;

use Livewire\Component;
use App\Models\TermsAndCondition;
use Illuminate\Support\Facades\Auth;

class EditTermsAndConditions extends Component
{
    public $termsId;
    public $title;
    public $version;
    public $content;
    public $locale = 'en';
    public $effective_date;
    public $requires_acceptance = true;
    public $is_active = false;
    
    // Metadata
    public $created_at;
    public $updated_at;
    public $created_by_name;
    
    // UI state
    public $showDeleteModal = false;

    protected function rules()
    {
        return [
            'title' => 'required|min:3|max:255',
            'version' => 'required|max:50',
            'content' => 'required|string',
            'locale' => 'required|in:en,es,fr,de',
            'effective_date' => 'nullable|date',
            'requires_acceptance' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'title.required' => 'Please provide a title for these terms.',
        'version.required' => 'Version number is required.',
        'content.required' => 'Please enter the terms content.',
    ];

    public function mount($id)
    {
        $this->termsId = $id;
        $this->loadTerms();
    }

    public function loadTerms()
    {
        $terms = TermsAndCondition::findOrFail($this->termsId);
        
        $this->title = $terms->title;
        $this->version = $terms->version;
        $this->content = $terms->content;
        $this->locale = $terms->locale ?? 'en';
        $this->effective_date = $terms->effective_date ? $terms->effective_date->format('Y-m-d\TH:i') : null;
        $this->requires_acceptance = $terms->requires_acceptance;
        $this->is_active = $terms->is_active;
        
        // Metadata
        $this->created_at = $terms->created_at;
        $this->updated_at = $terms->updated_at;
        $this->created_by_name = $terms->createdBy ? $terms->createdBy->name : 'Unknown';
        
        // Dispatch event to update CKEditor
        $this->dispatch('content-updated', content: $this->content);
    }

    public function generateVersion()
    {
        $this->version = 'v' . date('Y.m.d') . '.1';
    }

    public function update()
    {
        $this->validate();

        $terms = TermsAndCondition::findOrFail($this->termsId);
        
        $terms->update([
            'title' => $this->title,
            'version' => $this->version,
            'content' => $this->content,
            'locale' => $this->locale,
            'effective_date' => $this->effective_date,
            'requires_acceptance' => $this->requires_acceptance,
            'is_active' => $this->is_active,
            'updated_by' => Auth::id(),
        ]);

        session()->flash('success', 'Terms and Conditions updated successfully!');

        return redirect()->route('admin.terms');
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $terms = TermsAndCondition::findOrFail($this->termsId);
        $terms->delete();

        session()->flash('success', 'Terms and Conditions deleted successfully!');
        
        return redirect()->route('admin.terms');
    }

    public function render()
    {
        return view('livewire.admin.settings.terms.edit-terms-and-conditions');
    }
}