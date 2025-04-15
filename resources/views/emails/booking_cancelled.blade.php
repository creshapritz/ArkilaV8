<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancelled</title>
    <style>
        body {
            font-family: 'Sf Pro Display';
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .email-header h2 {
            font-size: 24px;
            color: #F07324;
            margin: 0;
        }
        .email-content {
            line-height: 1.6;
            font-size: 16px;
        }
        .email-content p {
            margin: 15px 0;
        }
        .email-content .car-details {
            font-weight: bold;
            color: #2e2e2e;
        }
        .email-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #888;
        }
        .footer-text {
            margin: 10px 0;
        }
        .cta-button {
            display: inline-block;
            padding: 12px 20px;
            background-color: #F07324;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }
        .cta-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Booking Cancelled</h2>
        </div>

        <div class="email-content">
            <p>Hello {{ $booking->client->first_name }},</p>

            <p>We regret to inform you that your booking for the <span class="car-details">{{ $booking->car_name }}</span>, scheduled for <span class="car-details">{{ \Carbon\Carbon::parse($booking->pickup_date)->toFormattedDateString() }}</span>, has been successfully cancelled.</p>

            <p>We have received your payment of <span class="car-details">₱1500</span> as the cancellation fee. Thank you for informing us in advance.</p>

            <p>If you have any questions or concerns, please do not hesitate to reach out to us.</p>

         
        </div>

        <div class="email-footer">
            <p class="footer-text">Best regards,</p>
            <p class="footer-text">ARKILA Car Rental</p>
           
        </div>
    </div>
</body>
</html>
