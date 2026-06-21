<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\StockHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    // PDF Laporan Transaksi
    public function transaction(Request $request)
    {
        $user  = Auth::user();
        $query = Transaction::with(['branch', 'kasir', 'details.product'])
                            ->latest('tanggal');

        if (! $user->hasRole('owner')) {
            $query->where('branch_id', $user->branch_id);
        }

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        $transactions = $query->get();
        $total        = $transactions->sum('total');

        $pdf = Pdf::loadView('pdf.transaction', compact('transactions', 'total', 'request'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-transaksi-' . now()->format('Ymd') . '.pdf');
    }

    // PDF Laporan Stok
    public function stock(Request $request)
    {
        $user  = Auth::user();
        $query = StockHistory::with(['product', 'branch', 'creator'])->latest();

        if (! $user->hasRole('owner')) {
            $query->where('branch_id', $user->branch_id);
        }

        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $histories = $query->get();

        $pdf = Pdf::loadView('pdf.stock', compact('histories', 'request'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-stok-' . now()->format('Ymd') . '.pdf');
    }

    // PDF struk transaksi single
    public function receipt(Transaction $transaction)
    {
        $transaction->load(['branch', 'kasir', 'details.product']);

        $pdf = Pdf::loadView('pdf.receipt', compact('transaction'))
                  ->setPaper([0, 0, 226.77, 500], 'portrait'); // ukuran struk

        return $pdf->download('struk-' . $transaction->kode_transaksi . '.pdf');
    }
}