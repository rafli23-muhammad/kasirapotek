<?php

namespace App\Exports;

use App\Models\Transactions;
use App\Models\Settings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportSalesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $transactions;
    protected $settings;
    protected $counter = 1;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
        $this->settings = Settings::first();
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function map($transaction): array
    {
        $taxPercentage = $this->settings ? ($this->settings->tax_percentage ?? 0) : 0;
        $taxAmount = $transaction->total * ($taxPercentage / 100);

        return [
            $this->counter++, 
            $transaction->invoice_code,
            $transaction->total,
            -$transaction->discount_total,
            $taxAmount,
            $transaction->grand_total,
            $transaction->payment_method,
            $transaction->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Invoice',
            'Pendapatan',
            'Diskon',
            'Pajak',
            'Total Pendapatan',
            'Metode Pembayaran',
            'Transaksi Pada',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4B4B4B'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle("A2:I{$highestRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 25,
            'G' => 25,
            'H' => 25,
        ];
    }
}
