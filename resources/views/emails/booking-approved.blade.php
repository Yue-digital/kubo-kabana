<h1>Booking Approved</h1>
<p>Hello {{ $booking->full_name }},</p>
<p>Your booking (ID: {{ $booking->id }}) has been approved!</p>
<a href="{{$checkoutUrl}}">Pay here</a>
<p>Thank you.</p>
