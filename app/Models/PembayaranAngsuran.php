<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranAngsuran extends Model
{
    protected $table = 'pembayaran_angsuran';

    protected $fillable = [
        'pinjaman_id',
        'bulan',
        'jumlah',
        'dicatat_oleh',
    ];

    protected function casts(): array
    {
        return [
            'bulan' => 'date',
            'jumlah' => 'decimal:2',
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
}
