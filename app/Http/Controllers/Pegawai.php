<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Pegawai extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'anggota')->latest();

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->cari . '%')
                  ->orWhere('nip_nrp', 'like', '%' . $request->cari . '%');
            });
        }

        $pegawai = $query->paginate(15)->withQueryString();

        return view('admin.pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        return view('admin.pegawai.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip_nrp' => ['required', 'string', 'max:18', 'unique:users,nip_nrp'],
            'pangkat_gol' => ['nullable', 'string', 'max:255'],
            'jabatan_satker' => ['nullable', 'string', 'max:255'],
            'jenis_personel' => ['nullable', 'string', 'max:50'],
            'eselon' => ['nullable', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
        ]);

        User::create([
            ...$validated,
            'email' => str($validated['nip_nrp'])->slug() . '@usipa.internal',
            'password' => Hash::make($validated['nip_nrp']),
            'role' => 'anggota',
            'must_change_password' => true,
        ]);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Pegawai berhasil ditambahkan. Password awal = NIP/NRP (' . $validated['nip_nrp'] . ').');
    }

    public function show(User $pegawai)
    {
        abort_unless($pegawai->role === 'anggota', 404);
        return view('admin.pegawai.show', compact('pegawai'));
    }

    public function edit(User $pegawai)
    {
        return view('admin.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, User $pegawai)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nip_nrp' => ['required', 'string', 'max:18', Rule::unique('users', 'nip_nrp')->ignore($pegawai->id)],
            'pangkat_gol' => ['nullable', 'string', 'max:255'],
            'jabatan_satker' => ['nullable', 'string', 'max:255'],
            'jenis_personel' => ['nullable', 'string', 'max:50'],
            'eselon' => ['nullable', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
        ]);

        $pegawai->update($validated);

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil diperbarui.');
    }

    public function resetPassword(User $pegawai)
    {
        $pegawai->update([
            'password' => Hash::make($pegawai->nip_nrp),
            'must_change_password' => true,
        ]);

        return back()->with('success', 'Password ' . $pegawai->name . ' berhasil direset ke NIP/NRP (' . $pegawai->nip_nrp . ').');
    }

    public function destroy(User $pegawai)
    {
        $pegawai->delete();

        return redirect()->route('admin.pegawai.index')->with('success', 'Data pegawai berhasil dihapus.');
    }
}