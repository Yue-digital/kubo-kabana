<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmation</h1>
        </div>

        <p>Dear {{ $booking->full_name }},</p>

        <p>Thank you for choosing Kubo Kabana Beach & Resort! We're excited to welcome you for a relaxing and memorable stay by the sea.</p>

        <div class="section">
            <div class="section-title">📅 Booking Details</div>
            <p><strong>Guest Name:</strong> {{ $booking->full_name }}</p>
            <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('F j, Y') }}</p>
            <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('F j, Y') }}</p>
            <p><strong>Total Amount:</strong> ₱{{ number_format($booking->total, 2) }}</p>
        </div>

        <div class="section">
            <div class="section-title">💳 Payment Information</div>
            <p><strong>Total Amount:</strong> ₱{{ number_format($booking->total, 2) }}</p>
            <p><strong>Date of Payment Received:</strong> {{ $booking->updated_at ? \Carbon\Carbon::parse($booking->updated_at)->format('F j, Y') : 'Pending' }}</p>
        </div>

        <div class="section">
            <div class="section-title">🏖 Reminders for Your Stay</div>
            <ul>
                <li>Check-in time: 2:00 PM</li>
                <li>Check-out time: 11:00 AM</li>
                <li>Bring valid IDs upon arrival</li>
                <li>Outside food is allowed. No corkage fee.</li>
                <li>Pet policy: Mindful of your dog's "poop". Not allowed in the pool. There will be a ₱1000 fine if the pet is in the pool. Leash at all times. Recommended pets are in diaper, not required.</li>
            </ul>
        </div>

        <p>If you have any special requests, feel free to let us know. We're here to make your stay as comfortable and enjoyable as possible.</p>

        <p>Looking forward to hosting you soon!</p>

        <div class="footer">
            <p>Warm regards,<br>Kubo Kabana Beach & Resort</p>
            @if($home = \App\Models\Home::first())
                @if($home->phone)
                    <p>📞 {{ $home->phone }}</p>
                @endif
                @if($home->landline)
                    <p>📞 {{ $home->landline }}</p>
                @endif
                @if($home->email)
                    <p>📧 {{ $home->email }}</p>
                @endif
            @endif
            <p>📍 Barangay Beneg, Botalan, Zambales</p>
            <p>This is an automated message, please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>
