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
        // Ambil input barcode dari query string atau form
        $barcode = trim($request->query('barcode') ?? $request->input('barcode'));

        // Validasi jika barcode kosong
        if (!$barcode) {
            return response()->json([
                'message' => 'Barcode tidak diberikan'
            ], 400);
        }

        // Cari barang berdasarkan barcode
        $item = Item::where('barcode', $barcode)->first();

        if ($item) {
            return response()->json($item); // Return item langsung
        }

        return response()->json([
            'message' => 'Barang tidak ditemukan'
        ], 404);
    }
}
