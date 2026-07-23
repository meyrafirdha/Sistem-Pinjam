<?php

namespace App\Http\Controllers;

use App\Models\Pinjaman;
use App\Models\User;
use Illuminate\Http\Request;

class JuruBayar extends Controller
{
    /**
     * Daftar semua anggota yang punya pinjaman disetujui, beserta ringkasan tagihannya.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $users = User::where('role', 'anggota')
            ->whereHas('pinjaman', fn ($q) => $q->where('status', 'disetujui'))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%'.$search.'%')
                      ->orWhere('nip_nrp', 'like', '%'.$search.'%');
                });
            })
            ->with(['pinjaman' => fn ($q) => $q->where('status', 'disetujui')->with('cicilanAngsuran')])
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('juru-bayar.index', compact('users', 'search'));
    }

    /**
     * Detail tagihan satu anggota: semua pinjaman disetujui miliknya + rincian cicilan.
     */
    public function show(User $user)
    {
        $pinjaman = Pinjaman::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->with('cicilanAngsuran')
            ->latest()
            ->get();

        return view('juru-bayar.show', compact('user', 'pinjaman'));
    }
    
    public function rekap(Request $request)
    {
        $bulan = $request->input('bulan'); // format: 2026-07

        $query = Pinjaman::with('user')->where('status', 'disetujui')->latest();

        if ($bulan) {
            $tahunPilih = substr($bulan, 0, 4);
            $bulanPilih = substr($bulan, 5, 2);
            $query->whereYear('created_at', $tahunPilih)
                ->whereMonth('created_at', $bulanPilih);
        }

        $pinjaman = $query->get();

        $pinjamanPerBulan = $pinjaman->groupBy(function ($item) {
            return $item->created_at->format('Y-m');
        })->sortKeysDesc();

        $bulanTersedia = Pinjaman::where('status', 'disetujui')
            ->selectRaw("DISTINCT DATE_FORMAT(created_at, '%Y-%m') as bulan")
            ->orderByDesc('bulan')
            ->pluck('bulan');

        $totalPinjaman = $pinjaman->sum('jumlah_pinjaman');
        $totalDibayar = $pinjaman->sum(fn ($p) => $p->totalSudahDibayar());
        $totalSisa = $pinjaman->sum(fn ($p) => $p->sisaAngsuran() ?? 0);

        return view('juru-bayar.rekap', compact(
            'pinjamanPerBulan', 'bulanTersedia', 'bulan', 'totalPinjaman', 'totalDibayar', 'totalSisa'
        ));
    }
}