<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to Your Query - BixCash</title>
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
            background: linear-gradient(135deg, #021c47 0%, #76d37a 100%);
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
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .original-message-box {
            background-color: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .reply-box {
            background-color: #f0fff0;
            border-left: 4px solid #76d37a;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reply to Your Query</h1>
        </div>

        <div class="content">
            <div class="greeting">
                <p>Hello <strong>{{ $query->name }}</strong>,</p>
            </div>

            <p>Thank you for your patience. We have reviewed your query and here is our response:</p>

            <div class="reply-box">
                <strong style="display: block; margin-bottom: 10px; color: #021c47;">Our Reply:</strong>
                <div style="white-space: pre-wrap;">{{ $reply->message }}</div>
            </div>

            <div class="original-message-box">
                <strong style="display: block; margin-bottom: 10px; color: #3498db;">Your Original Message:</strong>
                <div style="white-space: pre-wrap; color: #666;">{{ $query->message }}</div>
            </div>

            <p>If you have any further questions or need additional assistance, please feel free to contact us again.</p>

            <p style="margin-top: 30px;">Best regards,<br><strong>{{ $reply->user->name }}</strong><br>The BixCash Team</p>
        </div>

        <div class="footer">
            <p>This email was sent in response to your query submitted on {{ $query->created_at->format('F d, Y') }}.</p>
            <p>&copy; {{ date('Y') }} BixCash. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
