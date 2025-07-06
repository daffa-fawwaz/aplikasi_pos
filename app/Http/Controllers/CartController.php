<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;

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
            'jumlah' => 'required|integer|min:1',
        ]);

        // Cek apakah item sudah ada di cart
        $cartItem = CartItem::where('item_id', $item->id)->first();

        if ($cartItem) {
            $cartItem->jumlah += $request->jumlah;
            $cartItem->save();
        } else {
            CartItem::create([
                'item_id' => $item->id,
                'jumlah' => $request->jumlah,
            ]);
        }

        return back()->with('success', 'Item berhasil ditambahkan ke keranjang.');
    }

    public function remove(CartItem $cartItem)
    {
        $cartItem->delete();
        return redirect()->route('cart.index')->with('success', 'Barang di keranjang berhasil dihapus');
    }

    public function checkout()
    {

        DB::beginTransaction();

        try {
            $cartItems = CartItem::with('item')->get();

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Keranjang kosong.');
            }

            foreach ($cartItems as $cartItem) {
                $item = $cartItem->item;

                if (!$item) {
                    return back()->with('error', "Item tidak ditemukan.");
                }

                if ($item->stok < $cartItem->quantity) {
                    return back()->with('error', "Stok tidak cukup untuk {$item->nama_barang}.");
                }

                $item->stok -= $cartItem->quantity;
                $item->save();

                $transaction = Transaction::create([
                    'item_id' => $item->id,
                    'jumlah' => $cartItem->quantity,
                    'total_harga' => $item->harga_jual * $cartItem->quantity,
                    'harga_kulak' => $item->harga_beli,
                    'tanggal' => Carbon::now()->toDateString(),
                ]);

                $transactions[] = $transaction;
            }

            CartItem::truncate();

            $lastTransaction = end($transactions);
            $transaction = Transaction::with('item')->find($lastTransaction->id);

            $pdf = PDF::loadView('cart.nota', ['transactions' => $transactions]);
            return $pdf->download('nota-transaksi.pdf');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat checkout: ' . $e->getMessage());
        }
    }
}
