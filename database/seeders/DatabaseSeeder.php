<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Item::create([
            'nama_barang' => 'Alfamart Cotton Buds 100 pcs',
            'barcode' => '8994016000142',
            'stok' => 50,
            'harga_jual' => 8000,
            'harga_beli' => 5000,
            'tanggal_order' => Carbon::now()->format('Y-m-d'),
            'tipe_barang' => 'barang',
        ]);
    }
}
