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

        <p>Thank you for your interest in staying with us at <b>Kubo Kabana Beach & Resort!</b></p>

        <div class="details">
            <h2> Requested Booking Details</h2>
            <p><strong>Guest Name:</strong> {{ $booking->full_name }}</p>
            <p><strong>Check-in Date:</strong> {{ \Carbon\Carbon::parse($booking->check_in)->format('F j, Y') }}</p>
            <p><strong>Check-out Date:</strong> {{ \Carbon\Carbon::parse($booking->check_out)->format('F j, Y') }}</p>
            {{-- <p><strong>Number of Guests:</strong> {{ $booking-> }}</p> --}}
            <p><strong>Total Amount:</strong> ₱{{ number_format($booking->total, 2) }}</p>
        </div>

        <p>Please note that your reservation is currently under review and subject to availability and confirmation. We will get back to you as soon as possible to confirm whether your preferred dates and accommodation are available.
        </p>

        <p>To proceed, kindly wait for our next email or message with the final availability, total amount, and payment instructions. Once approved, we will request a down payment to secure your slot.</p>

        <p>Important Reminders:</p>
        <ul>
            <li>Availability is on a <b> first-come, first-served basis</b>.</li>
            <li>For faster processing, feel free to send us a copy of your ID and preferred payment method.</li>
        </ul>

        <p>We appreciate your patience and look forward to finalizing your booking soon!</p>

        <div class="footer">
            <p>Warm regards,<br>Kubo Kabana Beach & Resort</p>
            <p>📞 +639157234164</p>
            <p>This is an automated message, please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html> 