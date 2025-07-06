<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'nama_barang',
        'tipe_barang',
        'harga_jual',
        'harga_beli',
        'tanggal_order',
        'stok',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
