<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class BarcodeController extends Controller
{
    public function index()
    {
        return view('barcode.index');
    }

    public function search(Request $request)
    {
        $barcode = $request->query('barcode') ?? $request->input('barcode');
        $item = Item::where('barcode', $barcode)->first();

        // Jika request via AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return $item
                ? response()->json($item)
                : response()->json(['message' => 'Barang tidak ditemukan'], 404);
        }

        // Jika request normal (non-AJAX), tampilkan halaman detail atau redirect dengan pesan error
        if ($item) {
            return view('items.show', compact('item'));
        }

        return redirect()->back()->with('error', 'Barang dengan barcode ini tidak ditemukan!');
    }
}
