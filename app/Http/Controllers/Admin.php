<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin extends Controller
{
    public function dashboard()
    {
        $pinjaman = Pinjaman::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('pinjaman'));
    }

    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');
        $tanggal = $request->query('tanggal');

        $pinjaman = Pinjaman::with('user')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nip_nrp', 'like', '%' . $search . '%');
                });
            })
            ->when($tanggal, fn ($query) => $query->whereDate('created_at', $tanggal))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'pending' => Pinjaman::where('status', 'pending')->count(),
            'disetujui' => Pinjaman::where('status', 'disetujui')->count(),
            'ditolak' => Pinjaman::where('status', 'ditolak')->count(),
        ];

        return view('admin.pinjaman.index', compact('pinjaman', 'status', 'counts', 'search', 'tanggal'));
    }
    
    public function show(Pinjaman $pinjaman)
    {
        $pinjaman->load('user', 'processedBy');

        return view('admin.pinjaman.show', compact('pinjaman'));
    }

    public function acc(Pinjaman $pinjaman)
    {
        $pinjaman->update([
            'status' => 'disetujui',
            'catatan_admin' => null,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.pinjaman.show', $pinjaman)->with('success', 'Pengajuan pinjaman disetujui.');
    }

    public function decline(Request $request, Pinjaman $pinjaman)
    {
        $request->validate([
            'catatan_admin' => ['required', 'string', 'max:1000'],
        ]);

        $pinjaman->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->input('catatan_admin'),
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        return redirect()->route('admin.pinjaman.show', $pinjaman)->with('success', 'Pengajuan pinjaman ditolak.');
    }

    public function updateJuruBayar(Request $request, Pinjaman $pinjaman)
    {
        $validated = $request->validate([
            'nama_juru_bayar' => ['nullable', 'string', 'max:255'],
            'nrp_juru_bayar' => ['nullable', 'string', 'max:50'],
        ]);

        $pinjaman->update($validated);

        return redirect()->route('admin.pinjaman.show', $pinjaman)->with('success', 'Data Juru Bayar berhasil diperbarui.');
    }
}