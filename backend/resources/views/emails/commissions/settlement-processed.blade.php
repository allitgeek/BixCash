@component('mail::message')
{{-- Header with gradient --}}
<div style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); padding: 2rem; text-align: center; margin: -16px -16px 32px -16px; border-radius: 8px 8px 0 0;">
    <h1 style="color: white; margin: 0; font-size: 24px;">💵 Commission Settlement Processed</h1>
    <p style="color: rgba(255,255,255,0.9); margin: 8px 0 0 0; font-size: 14px;">{{ $formattedPeriod }}</p>
</div>

Hello **{{ $partner->name }}**!

Great news! Your commission payment for **{{ $formattedPeriod }}** has been processed.

@component('mail::panel')
### 💰 Settlement Details

<table style="width: 100%; margin: 16px 0;">
    <tr>
        <td style="padding: 8px 0; color: #666;">Period:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $formattedPeriod }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Amount Settled:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #00f2fe; font-size: 18px;">Rs {{ number_format($settlement->amount_settled, 2) }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Payment Method:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $settlement->formatted_payment_method }}</td>
    </tr>
    @if($settlement->settlement_reference)
    <tr>
        <td style="padding: 8px 0; color: #666;">Reference:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $settlement->settlement_reference }}</td>
    </tr>
    @endif
    <tr>
        <td style="padding: 8px 0; color: #666;">Processed Date:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $settlement->processed_at->format('M d, Y h:i A') }}</td>
    </tr>
</table>
@endcomponent

@if($ledger->amount_outstanding > 0)
@component('mail::panel')
### 📌 Remaining Balance

Your remaining outstanding balance for this period is **Rs {{ number_format($ledger->amount_outstanding, 2) }}**.

This will be settled in the next payment cycle.
@endcomponent
@else
<div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 16px; margin: 24px 0;">
    <p style="margin: 0; color: #155724; font-weight: 600; text-align: center;">✅ This commission has been fully settled!</p>
</div>
@endif

@component('mail::button', ['url' => route('partner.commissions.show', $ledger->id), 'color' => 'success'])
📄 View Settlement Details
@endcomponent

Thank you for your continued partnership with BixCash!

Best regards,<br>
**The BixCash Team**

<div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 12px;">
    <p style="margin: 0;">This is an automated notification from BixCash Commission System</p>
    <p style="margin: 8px 0 0 0;">© {{ date('Y') }} BixCash. All rights reserved.</p>
</div>
@endcomponent
