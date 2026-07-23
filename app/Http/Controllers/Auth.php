<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Auth extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nip_nrp' => ['required', 'string', 'max:18'],
            'password' => ['required'],
        ]);

        if (\Illuminate\Support\Facades\Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended($this->redirectPathFor($request->user()));
        }

        return back()->withErrors([
            'nip_nrp' => 'NIP/NRP atau password salah.',
        ])->onlyInput('nip_nrp');
    }

    public function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.current_password' => 'Password lama yang kamu masukkan salah.',
        ]);

        $user = $request->user();
        $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        $user->must_change_password = false;
        $user->save();

        return redirect($this->redirectPathFor($user))->with('success', 'Password berhasil diganti.');
    }
    
    private function redirectPathFor($user): string
    {
        if ($user->isAdmin()) {
            return '/admin/dashboard';
        }

        if ($user->role === 'juru_bayar') {
            return '/juru-bayar/tagihan';
        }

        return '/dashboard';
    }
}