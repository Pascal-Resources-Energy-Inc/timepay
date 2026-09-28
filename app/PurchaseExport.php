<?php

namespace App;

use App\Purchase;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PurchaseExport implements FromArray, WithTitle, ShouldAutoSize, WithStyles, WithColumnWidths
{
    protected $from;
    protected $to;
    protected $userId;

    public function __construct($from, $to, $userId = null)
    {
        $this->from = $from;
        $this->to = $to;
        $this->userId = $userId;
    }

    public function array(): array
    {
        $query = Purchase::with('employee')
            ->whereBetween('created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])
            ->orderBy('created_at', 'desc');
        
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }
        
        $purchases = $query->get();

        $productColumns = [
            ['id' => 1, 'name' => '330g LPG', 'category' => '330G'],
            ['id' => 2, 'name' => '230g Cylinder', 'category' => '230G'],
            ['id' => 5, 'name' => 'Stove + 1 Gaz Lite 230g LPG filled cylinder', 'category' => 'Eazy Kalan (230g)'],
            ['id' => 6, 'name' => 'Stove + 1 Gaz Lite 330g LPG filled cylinder', 'category' => 'Eazy Kalan (330g)'],
            ['id' => 7, 'name' => 'Stove + 2 Gaz Lite 330g filled cylinder', 'category' => 'Eazy Kalan (330g)'],
            ['id' => 8, 'name' => 'Griller + 1 Gaz Lite 230g LPG filled cylinder', 'category' => 'LPG BBQ Grill (230g)'],
            ['id' => 9, 'name' => 'Griller + 1 Gaz Lite 330g filled cylinder', 'category' => 'LPG BBQ Grill (330g)'],
            ['id' => 10, 'name' => 'Griller + 2 Gaz Lite 330g LPG filled cylinder', 'category' => 'LPG BBQ Grill (330g)'],
            ['id' => 11, 'name' => 'Torch + 1 Gaz Lite 230g LPG filled cylinder', 'category' => 'LPG Torch (230g)'],
            ['id' => 12, 'name' => 'Torch + 1 Gaz Lite 330g filled cylinder', 'category' => 'LPG Torch (330g)'],
            ['id' => 13, 'name' => 'Torch + 2 Gaz Lite 330g LPG filled cylinder', 'category' => 'LPG Torch (330g)'],
        ];

        $groupedData = [];
        
        foreach ($purchases as $purchase) {
            $employeeNumber = $purchase->employee_number ?? 'N/A';
            
            if (!isset($groupedData[$employeeNumber])) {
                $employeeName = 'N/A';
                $workLocation = 'N/A';
                
                if ($purchase->employee) {
                    $employeeName = trim(
                        ($purchase->employee->first_name ?? '') . ' ' . 
                        ($purchase->employee->middle_name ?? '') . ' ' . 
                        ($purchase->employee->last_name ?? '')
                    );
                    $employeeName = preg_replace('/\s+/', ' ', $employeeName);
                    $workLocation = $purchase->employee->location ?? 'N/A';
                }
                
                $groupedData[$employeeNumber] = [
                    'employee_number' => $employeeNumber,
                    'employee_name' => $employeeName,
                    'work_location' => $workLocation,
                    'products' => [],
                    'total_amount' => 0
                ];
            }
            
            $items = DB::table('purchase_items')
                ->where('purchase_id', $purchase->id)
                ->get();
            
            foreach ($items as $item) {
                $productId = $item->product_id;
                if (!isset($groupedData[$employeeNumber]['products'][$productId])) {
                    $groupedData[$employeeNumber]['products'][$productId] = 0;
                }
                $groupedData[$employeeNumber]['products'][$productId] += $item->quantity;
            }
            
            $groupedData[$employeeNumber]['total_amount'] += $purchase->total_amount;
        }

        $data = [];
        
        $data[] = ['GazLite'];
        $data[] = ['Employee Purchase Orders'];

        $dateRange = date('M d, Y', strtotime($this->from)) . ' - ' . date('M d, Y', strtotime($this->to));
        $titleRow = ['Date Range: ' . $dateRange, '', '', '', 'QUANTITY - DEALER/END USER'];
        $data[] = $titleRow;
        
        $categoryRow = ['', '', ''];
        foreach ($productColumns as $product) {
            $categoryRow[] = $product['category'];
        }
        $categoryRow[] = '';
        $data[] = $categoryRow;
        
        $headerRow = [
            'Employee Number',
            'Employee Name',
            'Work Location',
        ];
        
        foreach ($productColumns as $product) {
            $headerRow[] = $product['name'];
        }
        
        $headerRow[] = 'Total Amount';
        
        $data[] = $headerRow;
        
        foreach ($groupedData as $employeeData) {
            $row = [
                $employeeData['employee_number'],
                $employeeData['employee_name'],
                $employeeData['work_location'],
            ];
            
            foreach ($productColumns as $product) {
                $quantity = isset($employeeData['products'][$product['id']]) 
                    ? $employeeData['products'][$product['id']] 
                    : '';
                $row[] = $quantity;
            }
            
            $row[] = '₱ ' . number_format($employeeData['total_amount'], 2);
            
            $data[] = $row;
        }
        
        return $data;
    }

    public function title(): string
    {
        return 'Employee Purchase Orders';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 25,
            'C' => 20,
            'D' => 12,
            'E' => 12,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 20,
            'L' => 20,
            'M' => 20,
            'N' => 20,
            'O' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastColumn = 'Q';

        $sheet->mergeCells('A1:' . $lastColumn . '1');  
        $sheet->mergeCells('A2:' . $lastColumn . '2'); 

        $sheet->mergeCells('A3:C3');
        $sheet->mergeCells('D3:' . $lastColumn . '3');

        $sheet->mergeCells('A4:C4');
        $sheet->mergeCells('F4:G4');
        $sheet->mergeCells('J4:K4');
        $sheet->mergeCells('M4:N4');
        $sheet->mergeCells('O4:Q4');

        $sheet->getStyle('A1:' . $lastColumn . '4')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'D3D3D3']
            ]
        ]);

        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        $sheet->getStyle('D5:N' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('Q5:Q' . $lastRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
            ],
        ]);

        return [];
    }
}