<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Commission Invoice - {{ $ledger->formatted_period }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; padding: 40px; background: white; color: #333; }
        .invoice-container { max-width: 900px; margin: 0 auto; background: white; }
        .header { text-align: center; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 3px solid #667eea; }
        .header h1 { color: #667eea; font-size: 32px; margin-bottom: 10px; }
        .header p { color: #6c757d; font-size: 14px; }
        .info-section { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
        .info-box h3 { color: #667eea; font-size: 14px; text-transform: uppercase; margin-bottom: 10px; }
        .info-box p { margin: 5px 0; font-size: 14px; }
        .summary-cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 30px; }
        .summary-card { background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; }
        .summary-card small { display: block; color: #6c757d; font-size: 11px; margin-bottom: 5px; }
        .summary-card strong { display: block; font-size: 20px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        thead { background: #667eea; color: white; }
        th, td { padding: 12px; text-align: left; border: 1px solid #dee2e6; font-size: 13px; }
        th { font-weight: bold; }
        tbody tr:nth-child(even) { background: #f8f9fa; }
        tfoot { background: #f8f9fa; font-weight: bold; }
        tfoot td { font-size: 14px; }
        .total-amount { font-size: 18px; color: #f5576c; }
        .footer { text-align: center; margin-top: 40px; padding-top: 20px; border-top: 2px solid #dee2e6; font-size: 12px; color: #6c757d; }
        .status-badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #f8d7da; color: #721c24; }
        .status-settled { background: #d4edda; color: #155724; }
        .status-partial { background: #fff3cd; color: #856404; }
        @media print {
            body { padding: 20px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="header">
            <h1>💰 BIXCASH COMMISSION INVOICE</h1>
            <p>Commission Statement for {{ $ledger->formatted_period }}</p>
        </div>

        <!-- Info Section -->
        <div class="info-section">
            <div class="info-box">
                <h3>Partner Information</h3>
                <p><strong>{{ $ledger->partner->name }}</strong></p>
                <p>{{ $ledger->partner->partnerProfile->business_name ?? 'N/A' }}</p>
                <p>{{ $ledger->partner->phone }}</p>
                <p>{{ $ledger->partner->email }}</p>
            </div>
            <div class="info-box" style="text-align: right;">
                <h3>Invoice Details</h3>
                <p><strong>Invoice #:</strong> INV-{{ str_pad($ledger->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p><strong>Period:</strong> {{ $ledger->formatted_period }}</p>
                <p><strong>Generated:</strong> {{ now()->format('M d, Y h:i A') }}</p>
                <p><strong>Status:</strong> 
                    @if($ledger->status === 'settled')
                        <span class="status-badge status-settled">SETTLED</span>
                    @elseif($ledger->status === 'partial')
                        <span class="status-badge status-partial">PARTIAL</span>
                    @else
                        <span class="status-badge status-pending">PENDING</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <small>Commission Rate</small>
                <strong>{{ number_format($ledger->commission_rate_used, 2) }}%</strong>
            </div>
            <div class="summary-card">
                <small>Total Transactions</small>
                <strong>{{ number_format($ledger->total_transactions) }}</strong>
            </div>
            <div class="summary-card">
                <small>Invoice Total</small>
                <strong>Rs {{ number_format($ledger->total_invoice_amount, 2) }}</strong>
            </div>
            <div class="summary-card">
                <small>Commission Owed</small>
                <strong style="color: #f5576c;">Rs {{ number_format($ledger->commission_owed, 2) }}</strong>
            </div>
        </div>

        <!-- Transaction Breakdown -->
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Transaction Code</th>
                    <th>Customer</th>
                    <th style="text-align: right;">Invoice Amount</th>
                    <th style="text-align: center;">Rate</th>
                    <th style="text-align: right;">Commission</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ledger->commissionTransactions as $transaction)
                    @php
                        $partnerTxn = $transaction->partnerTransaction;
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($partnerTxn->transaction_date)->format('M d, Y') }}</td>
                        <td>{{ $transaction->transaction_code }}</td>
                        <td>{{ $partnerTxn->customer->name ?? 'N/A' }}</td>
                        <td style="text-align: right;">Rs {{ number_format($transaction->invoice_amount, 2) }}</td>
                        <td style="text-align: center;">{{ number_format($transaction->commission_rate, 2) }}%</td>
                        <td style="text-align: right;"><strong>Rs {{ number_format($transaction->commission_amount, 2) }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>TOTAL:</strong></td>
                    <td style="text-align: right;"><strong>Rs {{ number_format($ledger->total_invoice_amount, 2) }}</strong></td>
                    <td></td>
                    <td style="text-align: right;"><strong class="total-amount">Rs {{ number_format($ledger->commission_owed, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Payment Summary -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; text-align: center;">
                <div>
                    <small style="display: block; color: #6c757d; margin-bottom: 5px;">Total Commission</small>
                    <strong style="font-size: 18px;">Rs {{ number_format($ledger->commission_owed, 2) }}</strong>
                </div>
                <div>
                    <small style="display: block; color: #6c757d; margin-bottom: 5px;">Amount Paid</small>
                    <strong style="font-size: 18px; color: #00f2fe;">Rs {{ number_format($ledger->amount_paid, 2) }}</strong>
                </div>
                <div>
                    <small style="display: block; color: #6c757d; margin-bottom: 5px;">Outstanding</small>
                    <strong style="font-size: 18px; color: #f5576c;">Rs {{ number_format($ledger->amount_outstanding, 2) }}</strong>
                </div>
            </div>
        </div>

        <!-- Settlement History -->
        @if($ledger->settlements->count() > 0)
            <h3 style="margin-bottom: 15px; color: #667eea;">Payment History</h3>
            <table style="margin-bottom: 30px;">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ledger->settlements as $settlement)
                        <tr>
                            <td>{{ $settlement->processed_at->format('M d, Y h:i A') }}</td>
                            <td style="color: #00f2fe;"><strong>Rs {{ number_format($settlement->amount_settled, 2) }}</strong></td>
                            <td>{{ $settlement->formatted_payment_method }}</td>
                            <td>{{ $settlement->settlement_reference ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p><strong>BixCash</strong> - Commission Management System</p>
            <p style="margin-top: 10px;">This is a computer-generated invoice. For questions, contact BixCash admin.</p>
            <p style="margin-top: 10px;">Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
        </div>

        <!-- Print Button (hidden when printed) -->
        <div class="no-print" style="text-align: center; margin-top: 30px;">
            <button onclick="window.print()" style="background: #667eea; color: white; border: none; padding: 15px 40px; border-radius: 8px; font-size: 16px; cursor: pointer;">
                🖨️ Print Invoice
            </button>
            <button onclick="window.close()" style="background: #6c757d; color: white; border: none; padding: 15px 40px; border-radius: 8px; font-size: 16px; cursor: pointer; margin-left: 10px;">
                Close
            </button>
        </div>
    </div>
</body>
</html>
