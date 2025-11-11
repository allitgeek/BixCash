<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Withdrawal Rejected</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 28px;">❌ Withdrawal Rejected</h1>
    </div>
    
    <div style="background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e0e0e0;">
        <p style="font-size: 16px; margin-bottom: 20px;">Dear <strong>{{ $withdrawal->user->name }}</strong>,</p>
        
        <p style="font-size: 16px; margin-bottom: 20px;">
            We regret to inform you that your withdrawal request has been rejected by our team.
        </p>
        
        <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #dc3545; margin: 25px 0;">
            <h3 style="margin-top: 0; color: #dc3545;">Withdrawal Details</h3>
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
                    <td style="padding: 8px 0; font-weight: bold;">Status:</td>
                    <td style="padding: 8px 0;"><span style="background: #dc3545; color: white; padding: 4px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">❌ Rejected</span></td>
                </tr>
            </table>
        </div>
        
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #721c24;">Reason for Rejection:</h4>
            <p style="margin: 0; font-size: 14px; color: #721c24;">
                {{ $withdrawal->rejection_reason }}
            </p>
        </div>
        
        <div style="background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; font-size: 14px; color: #0c5460;">
                <strong>💰 Amount Refunded:</strong> The withdrawal amount of Rs {{ number_format($withdrawal->amount, 2) }} has been refunded back to your wallet.
            </p>
        </div>
        
        <p style="font-size: 14px; color: #666; margin-top: 25px;">
            If you believe this rejection was made in error or if you need clarification, please contact our support team. You may submit a new withdrawal request after addressing the rejection reason.
        </p>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="https://bixcash.com/customer/wallet" style="display: inline-block; background: #76d37a; color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: 600;">
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
