<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class Profile extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            // Admin cuma bisa ganti NIP/NRP (kredensial), bukan data personel
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'nip_nrp' => ['required', 'string', 'max:18', Rule::unique('users', 'nip_nrp')->ignore($user->id)],
            ]);

            $user->update($validated);
        } else {
            // Anggota bisa edit data pribadi (bukan data kepegawaian resmi)
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'tempat_lahir' => ['nullable', 'string', 'max:255'],
                'tanggal_lahir' => ['nullable', 'date'],
            ]);

            $user->update($validated);
        }

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }
}