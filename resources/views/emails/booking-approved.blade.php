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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Booking Confirmation</h1>
        </div>

        <p>Dear {{ $booking->full_name }},</p>

        <p>Great news! Your requested dates are available, and we're excited to welcome you to Kubo Kabana Beach & Resort for a relaxing beach escape.</p>

        <p>Here are the details of your confirmed reservation:</p>

        <div class="details">
            <h2>📅 Reservation Details</h2>
            <p><strong>Guest Name:</strong> {{ $booking->full_name }}</p>
            <p><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('F j, Y') }}</p>
            <p><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('F j, Y') }}</p>
            <p><strong>Number of Guests:</strong> {{ $booking->adults }} Adults & {{ $booking->children }} Children</p>
            <p><strong>Total Amount:</strong> ₱{{ number_format($booking->total, 2) }}</p>
        </div>

        <p>To secure your reservation, please settle the full amount through PayMongo.</p>
        <p><a href="{{ $checkoutUrl }}" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">Pay here</a></p>

        <p>Once payment is made, kindly send a copy of the transaction slip and your valid ID to this email.</p>

        <p>⚠️ Please note: Reservations are confirmed only after payment has been received. Slots are limited and subject to a first-paid, first-reserved policy.</p>

        <p>⏰ Deadline for Payment: Within 72 hours (3 days)</p>

        <p>Let us know if you have any questions or need assistance. We look forward to having you at Kubo Kabana!</p>

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
            @if($home && $home->facebook)
                <p>📱 Follow us for updates: {{ $home->facebook }}</p>
            @endif
        </div>
    </div>
</body>
</html>
