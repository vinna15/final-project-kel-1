<?php

namespace App\Http\Controllers;

use App\Exports\TransactionExport;
use App\Exports\StockExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller
{
    public function transaction(Request $request)
    {
        $user     = Auth::user();
        $branchId = $user->hasRole('owner') ? null : $user->branch_id;

        return Excel::download(
            new TransactionExport(
                $request->dari,
                $request->sampai,
                $branchId
            ),
            'laporan-transaksi-' . now()->format('Ymd') . '.xlsx'
        );
    }

    public function stock(Request $request)
    {
        $user     = Auth::user();
        $branchId = $user->hasRole('owner') ? null : $user->branch_id;

        return Excel::download(
            new StockExport(
                $request->dari,
                $request->sampai,
                $request->jenis,
                $branchId
            ),
            'laporan-stok-' . now()->format('Ymd') . '.xlsx'
        );
    }
}