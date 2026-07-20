<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CicilanAngsuran extends Model
{
    protected $table = 'cicilan_angsuran';

    protected $fillable = [
        'pinjaman_id',
        'cicilan_ke',
        'tanggal_bayar',
        'jumlah_dipotong',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'date',
            'jumlah_dipotong' => 'decimal:2',
        ];
    }

    public function pinjaman()
    {
        return $this->belongsTo(Pinjaman::class);
    }

    public function dicatatOleh()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function sudahDibayar(): bool
    {
        return $this->tanggal_bayar !== null;
    }
}
