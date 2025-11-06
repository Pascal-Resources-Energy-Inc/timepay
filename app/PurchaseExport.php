<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PurchaseExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithEvents
{
    protected $from;
    protected $to;
    protected $userId;

    public function __construct($from, $to, $userId)
    {
        $this->from = $from;
        $this->to = $to;
        $this->userId = $userId;
    }

    public function collection()
    {
        return DB::table('purchases')
            ->leftJoin('users', 'purchases.user_id', '=', 'users.id')
            ->select(
                'purchases.created_at',
                'purchases.order_number',
                'purchases.sku',
                'users.name as employee_name',
                'purchases.employee_number',
                'purchases.employee_work_place',
                'purchases.total_items',
                'purchases.total_amount',
                'purchases.claimed_at',
                'purchases.claim_address',
                'purchases.giver_name',
                'purchases.status'
            )
            ->whereBetween('purchases.created_at', [$this->from . ' 00:00:00', $this->to . ' 23:59:59'])
            ->orderBy('purchases.created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Date Filed',
            'Order No.',
            'SKU',
            'Employee Name',
            'Employee Number',
            'Work Location',
            'Total Items',
            'Total Amount',
            'Date Claimed',
            'Location',
            'Process By',
            'Status'
        ];
    }

    public function map($purchase): array
    {    
        return [
            date('Y-m-d H:i', strtotime($purchase->created_at)),
            $purchase->order_number,
            $purchase->sku ?? 'N/A',
            $purchase->employee_name ?? 'N/A',
            $purchase->employee_number ?? 'N/A',
            $purchase->employee_work_place ?? 'N/A',
            $purchase->total_items,
            number_format($purchase->total_amount, 2),
            $purchase->claimed_at ? date('Y-m-d H:i', strtotime($purchase->claimed_at)) : 'Not Claimed',
            $purchase->claim_address ?? 'N/A',
            $purchase->giver_name ?? 'N/A',
            $purchase->status
        ];
    }

    public function title(): string
    {
        return 'Discounted LPG Refill';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling (now row 3)
            3 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E8E8E8']
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                $sheet->insertNewRowBefore(1, 2);
                
                $sheet->setCellValue('A1', 'Pascal Resources Energy Inc.');
                $sheet->mergeCells('A1:L1');
                
                $sheet->setCellValue('A2', 'Discounted LPG Refill');
                $sheet->mergeCells('A2:L2');
                
                $sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                $sheet->getStyle('A2:L2')->applyFromArray([
                    'font' => [
                        'size' => 12
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(20);
                
                foreach(range('A','L') as $col) { 
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}