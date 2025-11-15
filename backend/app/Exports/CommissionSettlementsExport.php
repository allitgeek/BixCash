<?php

namespace App\Exports;

use App\Models\CommissionSettlement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class CommissionSettlementsExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, WithColumnFormatting
{
    protected $partnerId;
    protected $paymentMethod;
    protected $fromDate;
    protected $toDate;

    public function __construct($partnerId = null, $paymentMethod = null, $fromDate = null, $toDate = null)
    {
        $this->partnerId = $partnerId;
        $this->paymentMethod = $paymentMethod;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function collection()
    {
        $query = CommissionSettlement::with(['partner.partnerProfile', 'ledger', 'processedByUser'])
            ->orderBy('processed_at', 'desc');

        if ($this->partnerId) {
            $query->where('partner_id', $this->partnerId);
        }

        if ($this->paymentMethod) {
            $query->where('payment_method', $this->paymentMethod);
        }

        if ($this->fromDate) {
            $query->where('processed_at', '>=', $this->fromDate);
        }

        if ($this->toDate) {
            $query->where('processed_at', '<=', \Carbon\Carbon::parse($this->toDate)->endOfDay());
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Settlement ID',
            'Partner Name',
            'Business Name',
            'Period',
            'Amount Settled (Rs)',
            'Payment Method',
            'Reference',
            'Processed By',
            'Processed At',
            'Admin Notes',
        ];
    }

    public function map($settlement): array
    {
        return [
            $settlement->id,
            $settlement->partner->name ?? 'N/A',
            $settlement->partner->partnerProfile->business_name ?? 'N/A',
            $settlement->ledger->batch_period ?? 'N/A',
            $settlement->amount_settled,
            $settlement->formatted_payment_method,
            $settlement->settlement_reference ?? '',
            $settlement->processedByUser->name ?? 'N/A',
            $settlement->processed_at->format('Y-m-d H:i:s'),
            $settlement->admin_notes ?? '',
        ];
    }

    public function title(): string
    {
        return 'Commission Settlements';
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
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Amount Settled
        ];
    }
}
