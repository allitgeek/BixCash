@component('mail::message')
{{-- Header with gradient --}}
<div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 2rem; text-align: center; margin: -16px -16px 32px -16px; border-radius: 8px 8px 0 0;">
    <h1 style="color: white; margin: 0; font-size: 24px;">💰 New Commission Statement</h1>
    <p style="color: rgba(255,255,255,0.9); margin: 8px 0 0 0; font-size: 14px;">{{ $formattedPeriod }}</p>
</div>

Hello **{{ $partner->name }}**!

Your commission for **{{ $formattedPeriod }}** has been calculated and is ready for review.

@component('mail::panel')
### 📊 Commission Summary

<table style="width: 100%; margin: 16px 0;">
    <tr>
        <td style="padding: 8px 0; color: #666;">Period:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ $formattedPeriod }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Commission Rate:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ number_format($ledger->commission_rate_used, 2) }}%</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Total Transactions:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">{{ number_format($ledger->total_transactions) }}</td>
    </tr>
    <tr>
        <td style="padding: 8px 0; color: #666;">Invoice Amount:</td>
        <td style="padding: 8px 0; text-align: right; font-weight: 600;">Rs {{ number_format($ledger->total_invoice_amount, 2) }}</td>
    </tr>
    <tr style="border-top: 2px solid #667eea;">
        <td style="padding: 12px 0 8px 0; color: #667eea; font-size: 16px; font-weight: 600;">Commission Owed:</td>
        <td style="padding: 12px 0 8px 0; text-align: right; color: #667eea; font-size: 18px; font-weight: 700;">Rs {{ number_format($ledger->commission_owed, 2) }}</td>
    </tr>
</table>
@endcomponent

This commission will be settled by BixCash according to our payment schedule. You can view the detailed breakdown and transaction history using the button below.

@component('mail::button', ['url' => route('partner.commissions.show', $ledger->id), 'color' => 'primary'])
📄 View Full Statement
@endcomponent

If you have any questions about this commission statement, please don't hesitate to contact us.

Thank you for partnering with BixCash!

Best regards,<br>
**The BixCash Team**

<div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb; text-align: center; color: #9ca3af; font-size: 12px;">
    <p style="margin: 0;">This is an automated notification from BixCash Commission System</p>
    <p style="margin: 8px 0 0 0;">© {{ date('Y') }} BixCash. All rights reserved.</p>
</div>
@endcomponent
