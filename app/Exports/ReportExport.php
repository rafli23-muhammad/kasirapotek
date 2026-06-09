<?php
namespace App\Exports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $itemsSold;

    public function __construct($itemsSold)
    {
        $this->itemsSold = $itemsSold;
    }

    /**
     * Get the data to export
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Products::all();
    }

    /**
     * Map the data to rows for the export
     *
     * @param mixed $product
     * @return array
     */
    public function map($product): array
    {
        return [
            $product->id, 
            $product->name, 
            $this->itemsSold[$product->id] ?? 0 
        ];
    }

    /**
     * Set the headings for the exported file
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Product', 
            'Terjual', 
        ];
    }

    /**
     * Apply styles to the sheet
     *
     * @param Worksheet $sheet
     * @return void
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], 
            ],
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

        $sheet->getStyle('A2:C' . ($sheet->getHighestRow()))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
    }

    /**
     * Set the column widths
     *
     * @return array
     */
    public function columnWidths(): array
    {
        return [
            'A' => 10,  
            'B' => 30,  
            'C' => 20, 
        ];
    }
}
