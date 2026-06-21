<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\BranchStock;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('owner')) {
            return $this->ownerDashboard();
        }

        if ($user->hasRole('manager')) {
            return $this->managerDashboard($user);
        }

        if ($user->hasRole('supervisor')) {
            return $this->supervisorDashboard($user);
        }

        if ($user->hasRole('kasir')) {
            return $this->kasirDashboard($user);
        }

        if ($user->hasRole('gudang')) {
            return $this->gudangDashboard($user);
        }

        abort(403, 'Role tidak dikenali.');
    }

    private function ownerDashboard()
    {
        $period = request('period', '7days'); // default 7 hari

        // Tentukan range tanggal berdasarkan period
        switch ($period) {
            case '30days':
                $startDate = now()->subDays(29)->startOfDay();
                $label     = '30 Hari Terakhir';
                $groupBy   = 'DATE(tanggal)';
                break;
            case 'monthly':
                $startDate = now()->startOfYear();
                $label     = 'Per Bulan (Tahun Ini)';
                $groupBy   = 'MONTH(tanggal)';
                break;
            default: // 7days
                $startDate = now()->subDays(6)->startOfDay();
                $label     = '7 Hari Terakhir';
                $groupBy   = 'DATE(tanggal)';
                break;
        }

        // Data penjualan sesuai period
        if ($period === 'monthly') {
            $salesData = Transaction::selectRaw('MONTH(tanggal) as periode, SUM(total) as total, COUNT(*) as jumlah')
                ->whereYear('tanggal', date('Y'))
                ->groupByRaw('MONTH(tanggal)')
                ->orderByRaw('MONTH(tanggal)')
                ->get();
        } else {
            $salesData = Transaction::selectRaw('DATE(tanggal) as periode, SUM(total) as total, COUNT(*) as jumlah')
                ->where('tanggal', '>=', $startDate)
                ->groupByRaw('DATE(tanggal)')
                ->orderByRaw('DATE(tanggal)')
                ->get();
        }

        // Transaksi per cabang
        $salesPerBranch = Transaction::selectRaw('branch_id, COUNT(*) as jumlah_transaksi, SUM(total) as total_penjualan')
            ->with('branch:id,nama_cabang')
            ->groupBy('branch_id')
            ->get();

        // 5 produk terlaris
        $topProducts = \App\Models\TransactionDetail::selectRaw('product_id, SUM(qty) as total_qty')
            ->with('product:id,nama_barang')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('dashboard.owner', compact(
            'salesData',
            'salesPerBranch',
            'topProducts',
            'period',
            'label',
        ), [
            'totalCabang'    => \App\Models\Branch::count(),
            'totalUser'      => \App\Models\User::count(),
            'totalBarang'    => \App\Models\Product::count(),
            'totalTransaksi' => Transaction::count(),
            'totalPenjualan' => Transaction::sum('total'),
        ]);
    }

    private function managerDashboard($user)
    {
        $branchId = $user->branch_id;

        $stokMenipis = BranchStock::where('branch_id', $branchId)
            ->where('stok', '<=', 10)
            ->with('product')
            ->get();

        return view('dashboard.manager', [
            'totalTransaksi'  => Transaction::where('branch_id', $branchId)->count(),
            'totalPenjualan'  => Transaction::where('branch_id', $branchId)->sum('total'),
            'stokMenipis'     => $stokMenipis,
        ]);
    }

    private function supervisorDashboard($user)
    {
        $branchId = $user->branch_id;
        $today    = today();

        return view('dashboard.supervisor', [
            'transaksiHariIni' => Transaction::where('branch_id', $branchId)
                ->whereDate('tanggal', $today)
                ->count(),
            'barangMasuk'      => StockHistory::where('branch_id', $branchId)
                ->where('jenis', 'masuk')
                ->whereDate('created_at', $today)
                ->count(),
            'barangKeluar'     => StockHistory::where('branch_id', $branchId)
                ->where('jenis', 'keluar')
                ->whereNull('transaction_id')
                ->whereDate('created_at', $today)
                ->count(),
        ]);
    }

    private function kasirDashboard($user)
    {
        $today = today();

        return view('dashboard.kasir', [
            'transaksiHariIni' => Transaction::where('kasir_id', $user->id)
                ->whereDate('tanggal', $today)
                ->count(),
            'penjualanHariIni' => Transaction::where('kasir_id', $user->id)
                ->whereDate('tanggal', $today)
                ->sum('total'),
        ]);
    }

    private function gudangDashboard($user)
    {
        $branchId = $user->branch_id;
        $today    = today();

        return view('dashboard.gudang', [
            'barangMasuk'  => StockHistory::where('branch_id', $branchId)
                ->where('jenis', 'masuk')
                ->whereDate('created_at', $today)
                ->count(),
            'barangKeluar' => StockHistory::where('branch_id', $branchId)
                ->where('jenis', 'keluar')
                ->whereNull('transaction_id')
                ->whereDate('created_at', $today)
                ->count(),
            'stokMenipis'  => BranchStock::where('branch_id', $branchId)
                ->where('stok', '<=', 10)
                ->with('product')
                ->get(),
        ]);
    }
}
