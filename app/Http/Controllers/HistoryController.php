<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class HistoryController extends Controller
{

    public function index(Request $request)
    {
        $query = Transaction::with('item');

        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('tanggal', $request->month);
        }

        $historyTransaction = $query->orderBy('tanggal', 'desc')->get();

        return view('history.index', compact('historyTransaction'));
    }
}
