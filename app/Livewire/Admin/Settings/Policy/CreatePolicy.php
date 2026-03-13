<?php

namespace App\Livewire\Admin\Settings\Policy;

use Livewire\Component;
use App\Models\Policy;
use App\Models\PrivacyPolicy;
use Illuminate\Support\Facades\Auth;

class CreatePolicy extends Component
{
    public $title;
    public $version;
    public $content;
    public $published_on;
    public $is_active = false;

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

    public function mount()
    {
        $this->generateVersion();
    }

    public function generateVersion()
    {
        $this->version = 'v' . date('Y.m.d') . '.1';
    }

    public function save()
    {
        $this->validate();

        $policy = PrivacyPolicy::create([
            'title' => $this->title,
            'version' => $this->version,
            'content' => $this->content,
            'published_on' => $this->published_on ?? now(),
            'is_active' => $this->is_active,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        session()->flash('success', 'Policy created successfully!');

        if ($this->is_active) {
            return redirect()->route('admin.policy');
        }

        return redirect()->route('admin.policy.edit', $policy->id);
    }

    public function render()
    {
        return view('livewire.admin.settings.policy.create-policy');
    }
}