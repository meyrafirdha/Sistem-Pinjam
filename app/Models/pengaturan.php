<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';

    protected $fillable = [
        'nama_juru_bayar',
        'nrp_juru_bayar',
    ];

    /**
     * Selalu ada satu baris pengaturan (id = 1). Kalau belum ada, otomatis dibuatkan.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }
}