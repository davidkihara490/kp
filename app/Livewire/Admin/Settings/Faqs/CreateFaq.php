<?php

namespace App\Livewire\Admin\Settings\Faqs;

use Livewire\Component;
use App\Models\FAQ;

class CreateFaq extends Component
{
        public ?string $question;
    public ?string $answer;
    public bool $status = true;

    public function submit()
    {
        $this->validate([
            'question' => 'required|string|min:5|max:255',
            'answer' => 'required|string|min:10',
            'status' => 'required|boolean',
        ]);
        try {
            FAQ::create($this->all());

            return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
            $this->reset();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create FAQ: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.faqs.create-faq');
    }
}
