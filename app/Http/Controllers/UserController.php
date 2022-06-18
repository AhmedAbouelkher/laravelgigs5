<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function viewRegister()
    {
        return view('users.register');
    }

    public function register(Request $request)
    {
        $formFields = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed']
        ]);

        $formFields['password'] = bcrypt($formFields['password']);
        auth()->login(User::create($formFields));
        return redirect('/')->with('message', 'Thanks for registering!');
    }

    public function viewLogin()
    {
        return view('users.login');
    }

    public function login(Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();
            return redirect('/')->with('message', 'You are now logged in!');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.'
        ]);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }
}
