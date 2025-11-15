<?php

namespace App\Exports;

use App\Models\User;
use App\Models\CommissionTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class PartnerCommissionReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnFormatting
{
    protected $partnerId;

    public function __construct($partnerId)
    {
        $this->partnerId = $partnerId;
    }

    public function collection()
    {
        return CommissionTransaction::with(['partnerTransaction', 'ledger', 'batch'])
            ->where('partner_id', $this->partnerId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'Transaction Code',
            'Period',
            'Transaction Date',
            'Invoice Amount (Rs)',
            'Commission Rate (%)',
            'Commission Amount (Rs)',
            'Is Settled',
            'Settled At',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->transaction_code,
            $transaction->ledger->batch_period ?? 'N/A',
            $transaction->partnerTransaction->transaction_date->format('Y-m-d') ?? 'N/A',
            $transaction->invoice_amount,
            $transaction->commission_rate,
            $transaction->commission_amount,
            $transaction->is_settled ? 'Yes' : 'No',
            $transaction->settled_at ? $transaction->settled_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function title(): string
    {
        $partner = User::find($this->partnerId);
        return 'Partner Report - ' . ($partner->name ?? 'Unknown');
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
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Invoice Amount
            'F' => NumberFormat::FORMAT_NUMBER_00, // Rate
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Commission Amount
        ];
    }
}
