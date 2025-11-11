<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Withdrawal Approved</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">✅ Withdrawal Approved!</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e0e0e0;">
        <p style="font-size: 16px; margin-bottom: 20px;">Dear <strong>{{ $withdrawal->user->name }}</strong>,</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Great news! Your withdrawal request has been <strong style="color: #28a745;">approved</strong> and the payment has been processed.
        </p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #28a745; margin: 25px 0;">
            <h3 style="margin-top: 0; color: #28a745;">Payment Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; width: 40%;">Request ID:</td>
                    <td style="padding: 8px 0;">#{{ $withdrawal->id }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Amount:</td>
                    <td style="padding: 8px 0; font-size: 18px; color: #28a745;"><strong>Rs {{ number_format($withdrawal->amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Bank Reference:</td>
                    <td style="padding: 8px 0; font-family: monospace; font-weight: bold;">{{ $withdrawal->bank_reference }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Payment Date:</td>
                    <td style="padding: 8px 0;">{{ \Carbon\Carbon::parse($withdrawal->payment_date)->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Bank Account:</td>
                    <td style="padding: 8px 0;">{{ $withdrawal->account_number }} ({{ $withdrawal->bank_name }})</td>
                </tr>
            </table>
        </div>
        
        <div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; font-size: 14px; color: #155724;">
                <strong>✅ Payment Sent:</strong> The funds have been transferred to your bank account. Please allow 1-3 business days for the amount to reflect in your account depending on your bank's processing time.
            </p>
        </div>
        
        <p style="font-size: 14px; color: #666; margin-top: 25px;">
            Please keep the bank reference number for your records. If you have any questions about this transaction, feel free to contact our support team.
        </p>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="https://bixcash.com/customer/wallet" style="display: inline-block; background: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: 600;">
                View Wallet
            </a>
        </div>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
            <p style="margin: 0; font-size: 14px; color: #999;">
                © {{ date('Y') }} BixCash. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
