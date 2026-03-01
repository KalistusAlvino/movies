<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('movies.index');
        }
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('movies.index'));
        }

        return back()
            ->withErrors([
                'username' => trans('messages.invalid_credentials'),
            ])->onlyInput('username');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
