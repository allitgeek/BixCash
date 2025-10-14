<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Customer Query</title>
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
        .info-row {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }
        .value {
            color: #555;
        }
        .message-box {
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-top: 10px;
            border-radius: 4px;
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Customer Query Received</h1>
        </div>

        <div class="content">
            <p>You have received a new query from a customer through the BixCash website.</p>

            <div class="info-row">
                <div class="label">Customer Name:</div>
                <div class="value">{{ $query->name }}</div>
            </div>

            <div class="info-row">
                <div class="label">Email Address:</div>
                <div class="value">{{ $query->email }}</div>
            </div>

            <div class="info-row">
                <div class="label">Submitted On:</div>
                <div class="value">{{ $query->created_at->format('F d, Y \a\t h:i A') }}</div>
            </div>

            <div class="info-row">
                <div class="label">Message:</div>
                <div class="message-box">
                    {{ $query->message }}
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/admin/queries/' . $query->id) }}" class="button">View in Admin Panel</a>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated notification from BixCash Contact Form System.</p>
            <p>&copy; {{ date('Y') }} BixCash. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
