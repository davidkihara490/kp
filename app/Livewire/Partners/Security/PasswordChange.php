<?php

namespace App\Livewire\Partners\Security;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChange extends Component
{
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'current_password' => 'required|string',
        'password' => 'required|string|min:8|confirmed',
    ];

    protected $messages = [
        'current_password.required' => 'Please enter your current password.',
        'password.required' => 'Please enter a new password.',
        'password.min' => 'The new password must be at least 8 characters.',
        'password.confirmed' => 'The password confirmation does not match.',
    ];

    public function submit()
    {
        $this->validate();

        // Verify current password (if user is logged in)
        if (Auth::guard('partner')->check()) {
            if (!Hash::check($this->current_password, Auth::guard('partner')->user()->password)) {
                $this->addError('current_password', 'The current password is incorrect.');
                return;
            }
        }
        try {
            if (Auth::guard('partner')->check()) {

                $user = Auth::guard('partner')->user();
                $user->password = Hash::make($this->password);
                $user->save();

                $this->reset(['current_password', 'password', 'password_confirmation']);

                session()->flash('success', 'Your password has been changed successfully!');
            } else {
                session()->flash('warning', 'You are not logged in. Password change requires authentication.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to change password. Please try again later.');
        }
    }
    public function render()
    {
        return view('livewire.partners.security.password-change');
    }
}
