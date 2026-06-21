<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    public function __construct(
        protected $dari,
        protected $sampai,
        protected $branchId = null
    ) {}

    public function view(): View
    {
        $query = Transaction::with(['branch', 'kasir', 'details'])
                            ->latest('tanggal');

        if ($this->branchId) {
            $query->where('branch_id', $this->branchId);
        }

        if ($this->dari) {
            $query->whereDate('tanggal', '>=', $this->dari);
        }

        if ($this->sampai) {
            $query->whereDate('tanggal', '<=', $this->sampai);
        }

        $transactions = $query->get();
        $total        = $transactions->sum('total');

        return view('exports.transaction', compact('transactions', 'total'));
    }

    public function title(): string
    {
        return 'Laporan Transaksi';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 13]],
            3 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '334155'],
            ]],
        ];
    }
}