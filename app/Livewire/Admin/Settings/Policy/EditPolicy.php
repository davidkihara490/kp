<?php

namespace App\Livewire\Admin\Settings\Policy;

use Livewire\Component;
use App\Models\PrivacyPolicy;
use Illuminate\Support\Facades\Auth;

class EditPolicy extends Component
{
    public $policyId;
    public $title;
    public $version;
    public $content;
    public $published_on;
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
            'published_on' => 'nullable|date',
            'is_active' => 'boolean',
        ];
    }

    protected $messages = [
        'title.required' => 'Please provide a policy title.',
        'version.required' => 'Version number is required.',
        'content.required' => 'Please enter the policy content.',
    ];

    public function mount($id)
    {
        $this->policyId = $id;
        $this->loadPolicy();
    }

    public function loadPolicy()
    {
        $policy = PrivacyPolicy::with('createdBy')->findOrFail($this->policyId);
        
        $this->title = $policy->title;
        $this->version = $policy->version;
        $this->content = $policy->content;
        $this->published_on = $policy->published_on ? $policy->published_on->format('Y-m-d') : null;
        $this->is_active = $policy->is_active;
        
        // Metadata
        $this->created_at = $policy->created_at;
        $this->updated_at = $policy->updated_at;
        $this->created_by_name = $policy->createdBy ? $policy->createdBy->name : 'Unknown';
    }

    public function generateVersion()
    {
        $this->version = 'v' . date('Y.m.d') . '.1';
    }

    public function update()
    {
        $this->validate();

        $policy = PrivacyPolicy::findOrFail($this->policyId);
        
        $policy->update([
            'title' => $this->title,
            'version' => $this->version,
            'content' => $this->content,
            'published_on' => $this->published_on,
            'is_active' => $this->is_active,
            'updated_by' => Auth::id(),
        ]);

        session()->flash('success', 'Policy updated successfully!');

        return redirect()->route('admin.policy');
    }

    public function confirmDelete()
    {
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $policy = PrivacyPolicy::findOrFail($this->policyId);
        $policy->delete();

        session()->flash('success', 'Policy deleted successfully!');
        
        return redirect()->route('admin.policy');
    }

    public function render()
    {
        return view('livewire.admin.settings.policy.edit-policy');
    }
}