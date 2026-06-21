<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\StockHistory;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function transaction(Request $request)
    {
        $user  = Auth::user();
        $query = Transaction::with(['branch', 'kasir', 'details'])->latest('tanggal');

        if (! $user->hasRole('owner')) {
            $query->where('branch_id', $user->branch_id);
        }
        if ($request->filled('dari'))   $query->whereDate('tanggal', '>=', $request->dari);
        if ($request->filled('sampai')) $query->whereDate('tanggal', '<=', $request->sampai);

        $transactions = $query->paginate(15)->withQueryString();
        $branches     = $user->hasRole('owner') ? Branch::all() : collect();

        return view('reports.transaction', compact('transactions', 'branches'));
    }

    public function stock(Request $request)
    {
        $user  = Auth::user();
        $query = StockHistory::with(['product', 'branch', 'creator'])->latest();

        if (! $user->hasRole('owner')) {
            $query->where('branch_id', $user->branch_id);
        }
        if ($request->filled('dari'))   $query->whereDate('created_at', '>=', $request->dari);
        if ($request->filled('sampai')) $query->whereDate('created_at', '<=', $request->sampai);
        if ($request->filled('jenis'))  $query->where('jenis', $request->jenis);

        $histories = $query->paginate(15)->withQueryString();

        return view('reports.stock', compact('histories'));
    }
}