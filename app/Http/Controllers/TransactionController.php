<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function checkoutForm($id)
    {
        $item = Item::findOrFail($id);
        return view('items.checkout', compact('item'));
    }

    public function processCheckout(Request $request, $id)
    {
        $request->merge([
            'total_harga' => str_replace(['Rp', '.', ' '], '', $request->total_harga),
        ]);

        $request->validate([
            'jumlah_beli' => 'required|integer|min:1',
            'total_harga' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        $item = Item::findOrFail($id);

        if ($request->jumlah_beli > $item->stok) {
            return back()->withErrors(['jumlah_beli' => 'Jumlah beli melebihi stok tersedia']);
        }

        if ($request->total_harga <= $item->harga_beli) {
            return back()->withErrors(['total_harga' => 'Harga dibawah harga kulak']);
        }

        Transaction::create([
            'item_id' => $item->id,
            'jumlah' => $request->jumlah_beli,
            'total_harga' => number_format((float) $request->total_harga, 2, '.', ''),
            'harga_satuan' => $request->total_harga,
            'harga_kulak' => $item->harga_beli,
            'tanggal' => $request->tanggal,
        ]);

        $item->stok -= $request->jumlah_beli;
        $item->save();

        return redirect()->route('items.index')->with('success', 'Transaksi berhasil disimpan');
    }


    public function chartPendapatanBulanan()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // Ambil semua transaksi bulan ini + relasi item
        $transaksi = Transaction::with('item')
            ->whereYear('tanggal', $tahunIni)
            ->whereMonth('tanggal', $bulanIni)
            ->get();

        // Inisialisasi array minggu ke-n
        $mingguan = [
            'Minggu 1' => 0,
            'Minggu 2' => 0,
            'Minggu 3' => 0,
            'Minggu 4' => 0,
            'Minggu 5' => 0,
        ];

        foreach ($transaksi as $trx) {
            $tanggal = Carbon::parse($trx->tanggal);
            $mingguKe = ceil($tanggal->day / 7);
            $label = 'Minggu ' . $mingguKe;

            // Pastikan item tidak null
            if ($trx->item) {
                $keuntungan = $trx->total_harga - ($trx->item->harga_beli * $trx->jumlah);
                if (isset($mingguan[$label])) {
                    $mingguan[$label] += $keuntungan;
                }
            }
        }

        $labels = array_keys($mingguan);
        $data = array_values($mingguan);

        // Total Pendapatan (semua waktu)
        $totalPendapatan = Transaction::sum('total_harga');

        // Total Barang (sisa stok semua barang)
        $totalBarang = Item::sum('stok');

        // Total Harga Modal
        $totalHargaBarang = Item::sum(DB::raw('harga_beli * stok'));

        // Total Keuntungan (tanpa simpan di DB)
        $allTransactions = Transaction::with('item')->get();
        $totalKeuntungan = 0;
        foreach ($allTransactions as $trx) {
            if ($trx->item) {
                $totalKeuntungan += $trx->total_harga - ($trx->item->harga_beli * $trx->jumlah);
            }
        }

        return view('dashboard.index', compact(
            'labels',
            'data',
            'totalPendapatan',
            'totalBarang',
            'totalKeuntungan',
            'totalHargaBarang'
        ));
    }
}
