<?php

namespace App\Exports;

use App\Models\CommissionBatch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CommissionBatchesExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnFormatting
{
    protected $status;
    protected $search;

    public function __construct($status = null, $search = null)
    {
        $this->status = $status;
        $this->search = $search;
    }

    public function collection()
    {
        $query = CommissionBatch::query()->orderBy('batch_period', 'desc');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->search) {
            $query->where('batch_period', 'like', '%' . $this->search . '%');
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Batch ID',
            'Period',
            'Period Start',
            'Period End',
            'Status',
            'Triggered By',
            'Partners',
            'Transactions',
            'Transaction Amount (Rs)',
            'Commission Calculated (Rs)',
            'Started At',
            'Completed At',
        ];
    }

    public function map($batch): array
    {
        return [
            $batch->id,
            $batch->batch_period,
            $batch->period_start->format('Y-m-d'),
            $batch->period_end->format('Y-m-d'),
            ucfirst($batch->status),
            ucfirst($batch->triggered_by),
            $batch->total_partners ?? 0,
            $batch->total_transactions ?? 0,
            $batch->total_transaction_amount ?? 0,
            $batch->total_commission_calculated ?? 0,
            $batch->started_at ? $batch->started_at->format('Y-m-d H:i:s') : '',
            $batch->completed_at ? $batch->completed_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function title(): string
    {
        return 'Commission Batches';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Transaction Amount
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Commission
        ];
    }
}
