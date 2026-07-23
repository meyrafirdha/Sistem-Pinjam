<?php

namespace App\Http\Controllers;

use App\Models\CicilanAngsuran;
use App\Models\Pengaturan;
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

        $pinjaman = Pinjaman::with(['user', 'cicilanAngsuran'])
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('nip_nrp', 'like', '%'.$search.'%');
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

        $pengaturan = Pengaturan::current();

        return view('admin.pinjaman.index', compact('pinjaman', 'status', 'counts', 'search', 'tanggal', 'pengaturan'));
    }

    public function show(Pinjaman $pinjaman)
    {
        // Generate baris cicilan 1..N (N = jangka waktu) kalau belum ada, supaya admin tinggal isi.
        if ($pinjaman->isDisetujui()) {
            $pinjaman->pastikanCicilanTersedia();
        }

        $pinjaman->load('user', 'processedBy', 'cicilanAngsuran');

        return view('admin.pinjaman.show', compact('pinjaman'));
    }

    public function uploadSurat(Request $request, Pinjaman $pinjaman)
    {
        $request->validate([
            'file_surat_ttd' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $path = $request->file('file_surat_ttd')->store('surat-pinjaman', 'public');

        $pinjaman->update(['file_surat_ttd' => $path]);

        return redirect()->route('admin.pinjaman.show', $pinjaman)->with('success', 'Dokumen hardcopy berhasil diunggah.');
    }

    public function acc(Pinjaman $pinjaman)
    {
        $pengaturan = Pengaturan::current();

        $pinjaman->update([
            'status' => 'disetujui',
            'catatan_admin' => null,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
            // Kunci (snapshot) nama & NRP Juru Bayar yang berlaku SAAT INI.
            // Kalau nanti admin ganti nama Juru Bayar secara global, pengajuan
            // yang sudah di-ACC ini TIDAK ikut berubah.
            'nama_juru_bayar' => $pengaturan->nama_juru_bayar,
            'nrp_juru_bayar' => $pengaturan->nrp_juru_bayar,
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

        return redirect()->route('admin.pinjaman.show', $pinjaman)->with('danger', 'Pengajuan pinjaman ditolak.');
    }

    public function updateJuruBayarGlobal(Request $request)
    {
        $validated = $request->validate([
            'nama_juru_bayar' => ['nullable', 'string', 'max:255'],
            'nrp_juru_bayar' => ['nullable', 'string', 'max:50'],
        ]);

        Pengaturan::current()->update($validated);

        return redirect()->route('admin.pinjaman.index')->with('success', 'Nama & NRP Juru Bayar berhasil diperbarui untuk semua pengajuan.');
    }

    public function updateAngsuran(Request $request, Pinjaman $pinjaman)
    {
        $validated = $request->validate([
            'jumlah_angsuran' => ['nullable', 'numeric', 'min:0'],
        ]);

        $pinjaman->update($validated);

        return redirect()->route('admin.pinjaman.show', $pinjaman)->with('success', 'Jumlah angsuran berhasil disimpan.');
    }

    public function updateCicilan(Request $request, Pinjaman $pinjaman, CicilanAngsuran $cicilan)
    {
        if ($cicilan->pinjaman_id !== $pinjaman->id) {
            abort(404);
        }

        $validated = $request->validate([
            'tanggal_bayar' => ['nullable', 'date'],
            'jumlah_dipotong' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validated['dicatat_oleh'] = Auth::id();

        $cicilan->update($validated);

        return redirect()->route('admin.pinjaman.show', $pinjaman)->with('success', 'Cicilan ke-'.$cicilan->cicilan_ke.' berhasil disimpan.');
    }

    public function rekap(Request $request)
    {
        $bulan = $request->query('bulan', now()->format('Y-m'));

        $pinjaman = Pinjaman::with(['user', 'cicilanAngsuran'])
            ->where('status', 'disetujui')
            ->orderBy('id')
            ->get();

        return view('admin.pinjaman.rekap', compact('pinjaman', 'bulan'));
    }

    public function rekapKeseluruhan(\Illuminate\Http\Request $request)
    {
        $bulan = $request->input('bulan'); // format: 2026-07
        
        $query = \App\Models\Pinjaman::with('user')->latest();
        
        if ($bulan) {
            $tahunPilih = substr($bulan, 0, 4);
            $bulanPilih = substr($bulan, 5, 2);
            $query->whereYear('created_at', $tahunPilih)
                ->whereMonth('created_at', $bulanPilih);
        }
        
        $pinjaman = $query->get();
        
        // Kelompokkan per bulan untuk tampilan rekap
        $pinjamanPerBulan = $pinjaman->groupBy(function ($item) {
            return $item->created_at->format('Y-m');
        })->sortKeysDesc();
        
        // Daftar bulan yang tersedia untuk dropdown filter
        $bulanTersedia = \App\Models\Pinjaman::selectRaw("DISTINCT DATE_FORMAT(created_at, '%Y-%m') as bulan")
            ->orderByDesc('bulan')
            ->pluck('bulan');
        
        $totalBelumDibayar = $pinjaman->where('status', 'disetujui')
            ->sum(fn($p) => $p->sisaAngsuran() ?? 0);
        
        return view('admin.pinjaman.rekap-keseluruhan', compact(
            'pinjaman', 'pinjamanPerBulan', 'bulanTersedia', 'bulan', 'totalBelumDibayar'
        ));
    }

    public function cetakRekapSatu(Pinjaman $pinjaman)
    {
        $pinjaman->load('user', 'cicilanAngsuran');

        return view('admin.pinjaman.cetak-rekap', compact('pinjaman'));
    }

    public function juruBayarAkun()
    {
        $akun = \App\Models\User::where('role', 'juru_bayar')->first();

        return view('admin.juru-bayar-akun', compact('akun'));
    }

    public function updateJuruBayarPassword(Request $request)
    {
        $akun = \App\Models\User::where('role', 'juru_bayar')->firstOrFail();

        $request->validate([
            'nip_nrp' => ['required', 'string', 'max:18', \Illuminate\Validation\Rule::unique('users', 'nip_nrp')->ignore($akun->id)],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $akun->update([
            'nip_nrp' => $request->nip_nrp,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return back()->with('success', 'Username & password akun Juru Bayar berhasil diperbarui. Juru bayar sebelumnya tidak bisa login lagi.');
    }
}