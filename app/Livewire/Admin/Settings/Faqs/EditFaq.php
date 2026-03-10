<?php

namespace App\Livewire\Admin\Settings\Faqs;

use App\Models\FAQ;
use Livewire\Component;

class EditFaq extends Component
{
    public ?string $question;
    public ?string $answer;
    public bool $status = true;
    public $faq;

    public function mount($id)
    {
        $this->faq = FAQ::findOrFail($id);
        $this->question = $this->faq->question;
        $this->answer = $this->faq->answer;
        $this->status = $this->faq->status;
    }

    public function submit()
    {
        $this->validate([
            'question' => 'required|string|min:5|max:255',
            'answer' => 'required|string|min:10',
            'status' => 'required|boolean',
        ]);
        try {

            $this->faq->update([
                'question' => $this->question,
                'answer' => $this->answer,
                'status' => $this->status,
            ]);

            return redirect()->route('admin.faqs.index')->with('success', 'FAQ update successfully.');
            $this->reset();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update FAQ: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.settings.faqs.edit-faq');
    }
}
