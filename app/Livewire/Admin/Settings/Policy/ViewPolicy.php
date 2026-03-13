<?php

namespace App\Livewire\Admin\Settings\Policy;

use Livewire\Component;
use App\Models\Policy;
use App\Models\PrivacyPolicy;

class ViewPolicy extends Component
{
    public $policyId;
    public $title;
    public $version;
    public $content;
    public $published_on;
    public $is_active;
    
    // Metadata
    public $created_at;
    public $updated_at;
    public $created_by_name;
    public $updated_by_name;

    public function mount($id)
    {
        $this->policyId = $id;
        $this->loadPolicy();
    }

    public function loadPolicy()
    {
        $policy = PrivacyPolicy::with(['createdBy', 'updatedBy'])->findOrFail($this->policyId);
        
        $this->title = $policy->title;
        $this->version = $policy->version;
        $this->content = $policy->content;
        $this->published_on = $policy->published_on;
        $this->is_active = $policy->is_active;
        
        // Metadata
        $this->created_at = $policy->created_at;
        $this->updated_at = $policy->updated_at;
        $this->created_by_name = $policy->createdBy ? $policy->createdBy->name : 'System';
        $this->updated_by_name = $policy->updatedBy ? $policy->updatedBy->name : null;
    }

    public function render()
    {
        return view('livewire.admin.settings.policy.view-policy');
    }
}