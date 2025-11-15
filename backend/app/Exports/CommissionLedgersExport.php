<?php

namespace App\Exports;

use App\Models\CommissionLedger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CommissionLedgersExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnFormatting
{
    protected $batchId;
    protected $partnerId;

    public function __construct($batchId = null, $partnerId = null)
    {
        $this->batchId = $batchId;
        $this->partnerId = $partnerId;
    }

    public function collection()
    {
        $query = CommissionLedger::with(['partner.partnerProfile', 'batch'])
            ->orderBy('batch_period', 'desc');

        if ($this->batchId) {
            $query->where('batch_id', $this->batchId);
        }

        if ($this->partnerId) {
            $query->where('partner_id', $this->partnerId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Ledger ID',
            'Partner Name',
            'Business Name',
            'Period',
            'Commission Rate (%)',
            'Transactions',
            'Invoice Amount (Rs)',
            'Commission Owed (Rs)',
            'Amount Paid (Rs)',
            'Outstanding (Rs)',
            'Status',
            'Fully Settled At',
        ];
    }

    public function map($ledger): array
    {
        return [
            $ledger->id,
            $ledger->partner->name ?? 'N/A',
            $ledger->partner->partnerProfile->business_name ?? 'N/A',
            $ledger->batch_period,
            $ledger->commission_rate_used,
            $ledger->total_transactions,
            $ledger->total_invoice_amount,
            $ledger->commission_owed,
            $ledger->amount_paid,
            $ledger->amount_outstanding,
            ucfirst($ledger->status),
            $ledger->fully_settled_at ? $ledger->fully_settled_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function title(): string
    {
        return 'Commission Ledgers';
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
            'E' => NumberFormat::FORMAT_NUMBER_00, // Rate
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Invoice Amount
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Commission Owed
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Amount Paid
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Outstanding
        ];
    }
}
