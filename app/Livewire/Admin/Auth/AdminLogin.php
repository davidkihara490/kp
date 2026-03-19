<?php

namespace App\Livewire\Admin\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminLogin extends Component
{
    public $email;
    public $password;

    public function mount()
    {
        $this->email = 'karibuparcels@gmail.com';
        $this->password = 'admin123KP';
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ];
    }

    public function submit()
    {
        $fields = $this->validate();

        if (! Auth::guard('admin')->attempt($fields)) {
                    logger()->error('Login failed', $fields);
            $this->addError('email', 'Invalid email or password');
            return;
        }

        session()->regenerate();
        return redirect()->route('admin.dashboard');
    }
    public function render()
    {
        return view('livewire.admin.auth.admin-login');
    }
}
