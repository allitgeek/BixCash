<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Contacting BixCash</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .highlight-box {
            background-color: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
        .checkmark {
            font-size: 48px;
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Thank You for Contacting Us!</h1>
        </div>

        <div class="content">
            <div class="checkmark">✓</div>

            <p>Dear {{ $query->name }},</p>

            <p>Thank you for reaching out to BixCash! We have successfully received your query and our team will review it shortly.</p>

            <div class="highlight-box">
                <strong>Your Message:</strong>
                <p style="margin-top: 10px;">{{ $query->message }}</p>
            </div>

            <p>We strive to respond to all inquiries within 24-48 hours. One of our team members will get back to you at <strong>{{ $query->email }}</strong> as soon as possible.</p>

            <p>If you have any urgent concerns or need immediate assistance, please don't hesitate to reach out to us directly.</p>

            <p style="margin-top: 30px;">Best regards,<br><strong>The BixCash Team</strong></p>
        </div>

        <div class="footer">
            <p>This is an automated confirmation email. Please do not reply directly to this message.</p>
            <p>&copy; {{ date('Y') }} BixCash. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
