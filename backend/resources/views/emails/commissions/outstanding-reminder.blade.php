@component('mail::message')

<div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); padding: 2rem; text-align: center; border-radius: 8px; margin-bottom: 2rem;">
    <h1 style="color: white; margin: 0; font-size: 2rem;">⏰ Commission Payment Reminder</h1>
</div>

# Hello {{ $partner->name }},

This is a friendly reminder that you have **outstanding commission payments** with BixCash.

## Outstanding Summary

<table style="width: 100%; border-collapse: collapse; margin: 1.5rem 0;">
    <tr style="background: #f8f9fa;">
        <td style="padding: 1rem; border: 1px solid #dee2e6; font-weight: 600;">Total Outstanding Amount</td>
        <td style="padding: 1rem; border: 1px solid #dee2e6; color: #f5576c; font-size: 1.25rem; font-weight: bold;">
            Rs {{ number_format($totalOutstanding, 2) }}
        </td>
    </tr>
    <tr>
        <td style="padding: 1rem; border: 1px solid #dee2e6; font-weight: 600;">Number of Pending Periods</td>
        <td style="padding: 1rem; border: 1px solid #dee2e6; font-size: 1.1rem;">
            {{ $ledgerCount }} {{ $ledgerCount === 1 ? 'period' : 'periods' }}
        </td>
    </tr>
    <tr style="background: #f8f9fa;">
        <td style="padding: 1rem; border: 1px solid #dee2e6; font-weight: 600;">Oldest Pending Period</td>
        <td style="padding: 1rem; border: 1px solid #dee2e6; font-size: 1.1rem;">
            {{ $oldestPeriod }}
        </td>
    </tr>
</table>

## What This Means

Your commission debt is tracked separately from your wallet balance. You can continue using BixCash services normally, but we kindly request settlement of outstanding commissions at your earliest convenience.

## Next Steps

Please review your commission details and arrange payment:

@component('mail::button', ['url' => route('partner.commissions.index'), 'color' => 'primary'])
View Commission Details
@endcomponent

If you have already made payment or have any questions, please contact our support team.

---

**Important:** This is an automated reminder. Commission payments are separate from your wallet transactions and do not affect your ability to process customer orders.

Thank you for your partnership,<br>
**{{ config('app.name') }} Team**

<div style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-left: 4px solid #667eea; border-radius: 4px;">
    <p style="margin: 0; font-size: 0.875rem; color: #6c757d;">
        <strong>Need help?</strong> Contact us at support@bixcash.com or call our helpline during business hours.
    </p>
</div>

@endcomponent
