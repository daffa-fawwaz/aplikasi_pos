<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;


class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with('item')->get();
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request, Item $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Cek apakah stok cukup
        if ($item->stok < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        // Kurangi stok
        $item->stok -= $request->quantity;
        $item->save();

        // Tambahkan ke keranjang
        $cartItem = CartItem::where('item_id', $item->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'item_id' => $item->id,
                'quantity' => $request->quantity,
            ]);
        }

        return back()->with('success', 'Item berhasil ditambahkan ke keranjang dan stok dikurangi.');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Barang di keranjang berhasil dihapus');
    }

    public function updateHarga(Request $request, $id)
    {
        $request->validate([
            'harga_manual' => 'required|integer|min:0',
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cartItem->harga_manual = $request->harga_manual;
        $cartItem->save();

        return back()->with('success', 'Harga berhasil diperbarui.');
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
        ]);

        try {
            $cartItems = CartItem::with('item')->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Keranjang kosong.');
            }

            $transactions = [];

            foreach ($cartItems as $cartItem) {
                $item = $cartItem->item;

                if ($cartItem->harga_manual === null) {
                    return back()->with('error', "Harga belum diisi untuk {$item->nama_barang}.");
                }

                $harga = $cartItem->harga_manual;

                if ($harga < $item->harga_beli) {
                    return back()->with('error', "Harga jual untuk '{$item->nama_barang}' tidak boleh lebih kecil dari harga kulak (Rp " . number_format($item->harga_beli, 0, ',', '.') . ").");
                }

                $transactions[] = Transaction::create([
                    'item_id' => $item->id,
                    'jumlah' => $cartItem->quantity,
                    'total_harga' => $harga, // Tidak dikali quantity
                    'harga_satuan' => $harga,
                    'tanggal' => Carbon::now()->toDateString(),
                    'nama_pembeli' => $request->nama_pembeli,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                ]);
            }

            CartItem::truncate();

            $total = collect($transactions)->sum('total_harga');

            $notaText = View::make('cart.nota_template', [
                'tanggal' => now()->format('d M y'),
                'nama_pembeli' => $request->nama_pembeli,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat,
                'transactions' => $transactions,
                'total' => $total,
            ])->render();

            $filename = storage_path('app/nota_' . now()->timestamp . '.txt');
            File::put($filename, $notaText);

            $printerName = 'EPSON_L5290_Series';
            exec("lp -o cpi=12 -o lpi=6 -o page-left=20 -d " . escapeshellarg($printerName) . " " . escapeshellarg($filename));

            return back()->with('success', 'Checkout dan cetak nota berhasil.');
        } catch (\Exception $e) {
            return back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}
