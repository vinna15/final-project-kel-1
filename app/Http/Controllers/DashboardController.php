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

        if ($user->hasRole('owner'))      return $this->ownerDashboard();
        if ($user->hasRole('manager'))    return $this->managerDashboard($user);
        if ($user->hasRole('supervisor')) return $this->supervisorDashboard($user);
        if ($user->hasRole('kasir'))      return $this->kasirDashboard($user);
        if ($user->hasRole('gudang'))     return $this->gudangDashboard($user);

        abort(403, 'Role tidak dikenali.');
    }

    private function ownerDashboard()
    {
        $salesPerMonth = Transaction::selectRaw('MONTH(tanggal) as bulan, YEAR(tanggal) as tahun, SUM(total) as total')
            ->whereYear('tanggal', date('Y'))
            ->groupByRaw('YEAR(tanggal), MONTH(tanggal)')
            ->orderByRaw('YEAR(tanggal), MONTH(tanggal)')
            ->get();

        $salesPerBranch = Transaction::selectRaw('branch_id, COUNT(*) as jumlah_transaksi, SUM(total) as total_penjualan')
            ->with('branch:id,nama_cabang')
            ->groupBy('branch_id')
            ->get();

        $topProducts = \App\Models\TransactionDetail::selectRaw('product_id, SUM(qty) as total_qty')
            ->with('product:id,nama_barang')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('dashboard.owner', compact('salesPerMonth', 'salesPerBranch', 'topProducts'), [
            'totalCabang'    => Branch::count(),
            'totalUser'      => \App\Models\User::count(),
            'totalBarang'    => Product::count(),
            'totalTransaksi' => Transaction::count(),
            'totalPenjualan' => Transaction::sum('total'),
        ]);
    }

    private function managerDashboard($user)
    {
        $branchId = $user->branch_id;
        $stokMenupis = BranchStock::where('branch_id', $branchId)
            ->where('stok', '<=', 10)
            ->with('product')
            ->get();

        return view('dashboard.manager', [
            'totalTransaksi' => Transaction::where('branch_id', $branchId)->count(),
            'totalPenjualan' => Transaction::where('branch_id', $branchId)->sum('total'),
            'stokMenupis'    => $stokMenupis,
        ]);
    }

    private function supervisorDashboard($user)
    {
        $branchId = $user->branch_id;
        $today    = today();

        return view('dashboard.supervisor', [
            'transaksiHariIni' => Transaction::where('branch_id', $branchId)->whereDate('tanggal', $today)->count(),
            'barangMasuk'      => StockHistory::where('branch_id', $branchId)->where('jenis', 'masuk')->whereDate('created_at', $today)->count(),
            'barangKeluar'     => StockHistory::where('branch_id', $branchId)->where('jenis', 'keluar')->whereNull('transaction_id')->whereDate('created_at', $today)->count(),
        ]);
    }

    private function kasirDashboard($user)
    {
        $today = today();

        return view('dashboard.kasir', [
            'transaksiHariIni' => Transaction::where('kasir_id', $user->id)->whereDate('tanggal', $today)->count(),
            'penjualanHariIni' => Transaction::where('kasir_id', $user->id)->whereDate('tanggal', $today)->sum('total'),
        ]);
    }

    private function gudangDashboard($user)
    {
        $branchId = $user->branch_id;
        $today    = today();

        return view('dashboard.gudang', [
            'barangMasuk'  => StockHistory::where('branch_id', $branchId)->where('jenis', 'masuk')->whereDate('created_at', $today)->count(),
            'barangKeluar' => StockHistory::where('branch_id', $branchId)->where('jenis', 'keluar')->whereNull('transaction_id')->whereDate('created_at', $today)->count(),
            'stokMenupis'  => BranchStock::where('branch_id', $branchId)->where('stok', '<=', 10)->with('product')->get(),
        ]);
    }
}