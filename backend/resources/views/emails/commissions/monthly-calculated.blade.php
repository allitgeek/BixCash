@component('mail::message')
{{-- Header with gradient --}}
<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 2rem; text-align: center; margin: -16px -16px 32px -16px; border-radius: 8px 8px 0 0;">
    <h1 style="color: white; margin: 0; font-size: 24px;">📊 Monthly Commission Calculated</h1>
    <p style="color: rgba(255,255,255,0.9); margin: 8px 0 0 0; font-size: 14px;">{{ $formattedPeriod }}</p>
</div>

Hello **{{ $admin->name }}**!

The monthly commission calculation for **{{ $formattedPeriod }}** has been completed successfully.

@component('mail::panel')
### 📈 Batch Summary

<table style="width: 100%; margin: 16px 0;">
    <tr>
        <td style="padding: 8px 0; color: #666;">Batch ID:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">#{{ $batch->id }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Period:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $formattedPeriod }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Total Partners:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ number_format($batch->total_partners) }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Total Transactions:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ number_format($batch->total_transactions) }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Transaction Amount:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">Rs {{ number_format($batch->total_transaction_amount, 2) }}</td>
    </tr>
    <tr style="border-top: 2px solid #f5576c;">
        <td style="padding: 12px 0 8px 0; color: #f5576c; font-size: 16px; font-weight: 600;">Total Commission:</td>
        <td style="padding: 12px 0 8px 0; text-align: right; color: #f5576c; font-size: 18px; font-weight: 700;">Rs {{ number_format($batch->total_commission_calculated, 2) }}</td>
    </tr>
</table>
@endcomponent

@component('mail::panel')
### ⚡ Batch Status

<div style="text-align: center; padding: 12px;">
    @if($batch->status === 'completed')
    <span style="background: #d4edda; color: #155724; padding: 8px 16px; border-radius: 20px; font-weight: 600; display: inline-block;">
        ✅ Completed
    </span>
    @elseif($batch->status === 'failed')
    <span style="background: #f8d7da; color: #721c24; padding: 8px 16px; border-radius: 20px; font-weight: 600; display: inline-block;">
        ❌ Failed
    </span>
    @else
    <span style="background: #fff3cd; color: #856404; padding: 8px 16px; border-radius: 20px; font-weight: 600; display: inline-block;">
        ⏳ {{ ucfirst($batch->status) }}
    </span>
    @endif
</div>

<p style="margin: 16px 0 0 0; color: #666; font-size: 14px; text-align: center;">
    Triggered by: <strong>{{ $batch->triggered_by === 'automatic' ? 'System (Cron)' : 'Manual (Admin)' }}</strong>
</p>
@endcomponent

@component('mail::button', ['url' => route('admin.commissions.batches.show', $batch->id), 'color' => 'primary'])
📋 View Batch Details
@endcomponent

You can now review individual partner commissions and process settlements as needed.

Best regards,<br>
**BixCash Commission System**

<div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 12px;">
    <p style="margin: 0;">This is an automated notification from BixCash Commission System</p>
    <p style="margin: 8px 0 0 0;">© {{ date('Y') }} BixCash. All rights reserved.</p>
</div>
@endcomponent
