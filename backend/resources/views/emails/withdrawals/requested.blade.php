<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Withdrawal Request Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #76d37a 0%, #93db4d 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">💰 Withdrawal Request Received</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e0e0e0;">
        <p style="font-size: 16px; margin-bottom: 20px;">Dear <strong>{{ $withdrawal->user->name }}</strong>,</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            Your withdrawal request has been received and is being processed by our team.
        </p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #76d37a; margin: 25px 0;">
            <h3 style="margin-top: 0; color: #76d37a;">Withdrawal Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; width: 40%;">Request ID:</td>
                    <td style="padding: 8px 0;">#{{ $withdrawal->id }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Amount:</td>
                    <td style="padding: 8px 0; font-size: 18px; color: #dc3545;"><strong>Rs {{ number_format($withdrawal->amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Bank:</td>
                    <td style="padding: 8px 0;">{{ $withdrawal->bank_name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Account:</td>
                    <td style="padding: 8px 0;">{{ $withdrawal->account_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold;">Status:</td>
                    <td style="padding: 8px 0;"><span style="background: #ffc107; color: #000; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">⏳ Pending</span></td>
                </tr>
            </table>
        </div>
        
        <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; font-size: 14px; color: #856404;">
                <strong>⚠️ Important:</strong> The amount has been deducted from your wallet. Withdrawal requests are typically processed within 24-48 business hours.
            </p>
        </div>
        
        <p style="font-size: 14px; color: #666; margin-top: 25px;">
            You will receive another email once your withdrawal has been processed.
        </p>
        
        <p style="font-size: 14px; color: #666; margin-top: 20px;">
            If you have any questions, please contact our support team.
        </p>
        
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0;">
            <p style="margin: 0; font-size: 14px; color: #999;">
                © {{ date('Y') }} BixCash. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
