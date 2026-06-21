<?php

namespace App\Exports;

use App\Models\StockHistory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockExport implements FromView, ShouldAutoSize, WithTitle, WithStyles
{
    public function __construct(
        protected $dari,
        protected $sampai,
        protected $jenis    = null,
        protected $branchId = null
    ) {}

    public function view(): View
    {
        $query = StockHistory::with(['product', 'branch', 'creator'])->latest();

        if ($this->branchId) {
            $query->where('branch_id', $this->branchId);
        }

        if ($this->dari) {
            $query->whereDate('created_at', '>=', $this->dari);
        }

        if ($this->sampai) {
            $query->whereDate('created_at', '<=', $this->sampai);
        }

        if ($this->jenis) {
            $query->where('jenis', $this->jenis);
        }

        $histories = $query->get();

        return view('exports.stock', compact('histories'));
    }

    public function title(): string
    {
        return 'Laporan Stok';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 13]],
            3 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => [
                'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F766E'],
            ]],
        ];
    }
}