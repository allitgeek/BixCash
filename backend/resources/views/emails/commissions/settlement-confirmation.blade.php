@component('mail::message')
{{-- Header with gradient --}}
<div style="background: linear-gradient(135deg, #76d37a 0%, #4facfe 100%); padding: 2rem; text-align: center; margin: -16px -16px 32px -16px; border-radius: 8px 8px 0 0;">
    <h1 style="color: white; margin: 0; font-size: 24px;">✅ Settlement Confirmation</h1>
    <p style="color: rgba(255,255,255,0.9); margin: 8px 0 0 0; font-size: 14px;">Commission Payment Processed</p>
</div>

Hello **{{ $admin->name }}**!

You have successfully processed a commission settlement.

@component('mail::panel')
### 💰 Settlement Summary

<table style="width: 100%; margin: 16px 0;">
    <tr>
        <td style="padding: 8px 0; color: #666;">Settlement ID:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">#{{ $settlement->id }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Partner:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $settlement->partner->name }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Business:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $settlement->partner->partnerProfile->business_name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Period:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $formattedPeriod }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Amount Settled:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #4facfe; font-size: 18px;">Rs {{ number_format($settlement->amount_settled, 2) }}</td>
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
        <td style="padding: 8px 0; color: #666;">Processed At:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $settlement->processed_at->format('M d, Y h:i A') }}</td>
    </tr>
</table>
@endcomponent

@if($settlement->admin_notes)
@component('mail::panel')
### 📝 Admin Notes

{{ $settlement->admin_notes }}
@endcomponent
@endif

@if($ledger->amount_outstanding > 0)
<div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 16px; margin: 24px 0;">
    <p style="margin: 0; color: #856404; text-align: center;">
        <strong>Remaining Balance:</strong> Rs {{ number_format($ledger->amount_outstanding, 2) }}
    </p>
</div>
@else
<div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 16px; margin: 24px 0;">
    <p style="margin: 0; color: #155724; font-weight: 600; text-align: center;">✅ Commission Fully Settled</p>
</div>
@endif

@component('mail::button', ['url' => route('admin.commissions.partners.show', $settlement->partner_id), 'color' => 'success'])
👤 View Partner Details
@endcomponent

A notification has been sent to the partner confirming this settlement.

Best regards,<br>
**BixCash Commission System**

<div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 12px;">
    <p style="margin: 0;">This is an automated confirmation from BixCash Commission System</p>
    <p style="margin: 8px 0 0 0;">© {{ date('Y') }} BixCash. All rights reserved.</p>
</div>
@endcomponent
