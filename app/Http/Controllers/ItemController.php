<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ItemController extends Controller
{

    public function index()
    {
        $items = Item::paginate(7);

        return view('items.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {

        $request->merge([
            // 'harga_jual' => str_replace(['Rp', '.', ' '], '', $request->harga_jual),
            'harga_beli' => str_replace(['Rp', '.', ' '], '', $request->harga_beli),
        ]);

        $validated = $request->validate([
            'nama_barang' => 'required',
            'tipe_barang' => 'required',
            // 'harga_jual' => 'required|numeric|gte:harga_beli',
            'harga_beli' => 'required|numeric',
            'tanggal_order' => 'required|date',
            'stok' => 'required|integer|min:0',
        ]);


        $existingItem = Item::where('nama_barang', $validated['nama_barang'])
            ->where('tipe_barang', $validated['tipe_barang'])
            ->first();

        if ($existingItem) {
            $existingItem->stok += $validated['stok'];
            $existingItem->save();

            return redirect('items')->with('success', 'Stok barang berhasil ditambahkan.');
        } else {
            Item::create($validated);

            return redirect('items')->with('success', 'Barang baru berhasil ditambahkan.');
        }
    }

    public function show(Item $item)
    {
        //
    }

    public function edit(Item $item)
    {
        //
    }

    public function update(Request $request, Item $item)
    {
        //
    }

    public function destroy(Item $item)
    {
        $item->forceDelete();

        return redirect('items')->with('success', 'Barang berhasil dihapus.');
    }

    public function updateStok(Request $request, Item $item)
    {
        $request->validate([
            'stok' => 'required|integer|min:0',
        ]);

        $item->stok = $request->stok;

        if ($item->stok == 0) {
            $item->delete();

            return response()->json([
                'success' => true,
                'deleted' => true,
                'message' => 'Item dihapus karena stok 0'
            ]);
        }

        $item->save();

        return response()->json([
            'success' => true,
            'deleted' => false,
            'message' => 'Stok berhasil diperbarui'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $items = Item::where('nama_barang', 'like', "%$query%")
            ->orWhere('tipe_barang', 'like', "%$query%")
            ->get();

        $html = view('items.partials.table-rows', compact('items'))->render();

        return response()->json(['html' => $html]);
    }
}
