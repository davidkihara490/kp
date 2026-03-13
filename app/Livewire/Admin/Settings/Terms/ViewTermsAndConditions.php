<?php

namespace App\Livewire\Admin\Settings\Terms;

use Livewire\Component;
use App\Models\TermsAndCondition;
use Illuminate\Support\Facades\Auth;

class ViewTermsAndConditions extends Component
{
    public $termsId;
    public $title;
    public $version;
    public $content;
    public $locale;
    public $effective_date;
    public $expiry_date;
    public $requires_acceptance;
    public $is_active;
    
    // Metadata
    public $created_at;
    public $updated_at;
    public $created_by_name;
    public $updated_by_name;
    
    // Statistics (optional - if you track these)
    public $acceptance_count = 0;
    public $views_count = 0;
    public $has_version_history = false;

    public function mount($id)
    {
        $this->termsId = $id;
        $this->loadTerms();
        $this->incrementViewCount();
    }

    public function loadTerms()
    {
        $terms = TermsAndCondition::findOrFail($this->termsId);
        
        $this->title = $terms->title;
        $this->version = $terms->version;
        $this->content = $terms->content;
        $this->locale = $terms->locale ?? 'en';
        $this->effective_date = $terms->effective_date;
        $this->expiry_date = $terms->expiry_date;
        $this->requires_acceptance = $terms->requires_acceptance;
        $this->is_active = $terms->is_active;
        
        // Metadata
        $this->created_at = $terms->created_at;
        $this->updated_at = $terms->updated_at;
        $this->created_by_name = $terms->createdBy ? $terms->createdBy->name : 'System';
        $this->updated_by_name = $terms->updatedBy ? $terms->updatedBy->name : null;
        
        // Check for version history (if using versioning)
        $this->has_version_history = false; // Implement if you have versioning
        
        // Load statistics (if you track them)
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        // Example: Load acceptance count from a user_acceptances table
        // $this->acceptance_count = $terms->acceptances()->count();
        
        // Example: Load view count from a views table
        // $this->views_count = $terms->views()->count();
    }

    public function incrementViewCount()
    {
        // Optional: Track views
        // You could create a terms_views table or use a package like Laravel Analytics
        // TermsAndConditionView::create([
        //     'terms_id' => $this->termsId,
        //     'user_id' => Auth::id(),
        //     'ip_address' => request()->ip(),
        //     'viewed_at' => now(),
        // ]);
    }

    public function copyToClipboard()
    {
        $url = route('admin.terms.view', $this->termsId);
        $this->dispatch('copied');
        
        // You'll need to handle the actual clipboard copy in JavaScript
        // This just triggers the event
        session()->flash('success', 'Link copied to clipboard!');
    }

    public function render()
    {
        return view('livewire.admin.settings.terms.view-terms-and-conditions');
    }
}