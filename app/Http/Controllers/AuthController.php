<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cari user
        $user = User::where('email', $credentials['email'])->first();

        // Cek user dan password
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->with('error', 'Email atau Password salah.');
        }

        // Cek role admin
        if ($user->role !== 'admin') {
            return back()->with('error', 'Anda bukan admin.');
        }

        // Login manual
        Auth::login($user);

        LogActivity::create([
            'user_id' => $user->id,
            'action' => 'login',
            'log' => 'Berhasil login',
            'ip_address' => $request->ip()
        ]);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
