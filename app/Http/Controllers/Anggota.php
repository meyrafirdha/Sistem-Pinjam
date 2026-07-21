<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Anggota extends Controller
{
    public function dashboard()
    {
        $pinjaman = Auth::user()->pinjaman()->latest()->take(5)->get();

        return view('anggota.dashboard', compact('pinjaman'));
    }

    public function index()
    {
        $pinjaman = Auth::user()->pinjaman()->latest()->paginate(10);

        return view('anggota.pinjaman.index', compact('pinjaman'));
    }

    public function create()
    {
        return view('anggota.pinjaman.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['nama_rekening'] = Auth::user()->name; // dikunci, tidak boleh diganti dari input manapun

        $pinjaman = Pinjaman::create($validated);

        return redirect()
            ->route('anggota.pinjaman.show', $pinjaman)
            ->with('success', 'Pengajuan pinjaman berhasil dikirim. Silakan tunggu persetujuan admin.');
    }

    public function show(Pinjaman $pinjaman)
    {
        $this->authorizeOwner($pinjaman);

        return view('anggota.pinjaman.show', compact('pinjaman'));
    }

    public function cetak(Pinjaman $pinjaman)
    {
        $this->authorizeOwner($pinjaman);

        return view('anggota.pinjaman.pdf', compact('pinjaman'));
    }

    public function unduhPdf(Pinjaman $pinjaman)
    {
        $this->authorizeOwner($pinjaman);

        $pdf = Pdf::loadView('anggota.pinjaman.pdf', ['pinjaman' => $pinjaman, 'download' => true])
            ->setPaper('a4');

        return $pdf->download('formulir-pinjaman-'.$pinjaman->id.'.pdf');
    }

    private function authorizeOwner(Pinjaman $pinjaman): void
    {
        if (Auth::user()->role !== 'admin' && $pinjaman->user_id !== Auth::id()) {
            abort(403);
        }
    }

    private function validateData(Request $request): array
    {
        // Catatan: jumlah_angsuran SENGAJA tidak divalidasi/diambil di sini.
        // Field itu sekarang HANYA diisi oleh admin lewat halaman Tinjau Pengajuan.
        $validated = $request->validate([
            'no_rekening' => ['required', 'string', 'max:50'],
            'nama_bank' => ['required', 'string', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'jumlah_pinjaman' => ['required', 'numeric', 'min:0'],
            'jangka_waktu' => ['required', 'string', 'max:50'],
            'punya_hutang_bank' => ['nullable', 'boolean'],
            'hutang_bank_nama' => ['nullable', 'required_if:punya_hutang_bank,1', 'string', 'max:255'],
            'hutang_bank_angsuran' => ['nullable', 'required_if:punya_hutang_bank,1', 'numeric', 'min:0'],
        ]);

        $validated['punya_hutang_bank'] = $request->boolean('punya_hutang_bank');

        return $validated;
    }
}