<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    use HasFactory;

    protected $table = 'pinjaman';

    protected $fillable = [
        'user_id',
        'no_rekening',
        'nama_rekening',
        'nama_bank',
        'no_hp',
        'jumlah_pinjaman',
        'jumlah_angsuran',
        'jangka_waktu',
        'punya_hutang_bank',
        'hutang_bank_nama',
        'hutang_bank_angsuran',
        'status',
        'catatan_admin',
        'processed_by',
        'processed_at',
        'nama_juru_bayar',
        'nrp_juru_bayar',
    ];

    protected function casts(): array
    {
        return [
            'punya_hutang_bank' => 'boolean',
            'jumlah_pinjaman' => 'decimal:2',
            'jumlah_angsuran' => 'decimal:2',
            'hutang_bank_angsuran' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => 'Menunggu Persetujuan',
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'disetujui' => 'bg-green-50 text-green-700 border-green-200',
            'ditolak' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-yellow-50 text-yellow-700 border-yellow-200',
        };
    }
}