@extends('pages.payment.index')
@section('payment_content')


<div class="col-md-12"> <h1 class="text-center">ACCOMODATION</h1></div>
<div class="col-md-5"></div>
<div class="col-md-6 payment-col">


            <div class="reservation-cont reservation-cont-success ">
                <h2>RESERVATION</h2>

                <img src="{{ Storage::url('kubo-brown.png') }}" alt="{{ config('app.name', 'Laravel') }}" height="50">

                <h2>THANK YOU</h2>

                <p>For choosing Kubo Kabana as your place to relax and unwind. Let us know what to improve more by  posting a review on our facebook page.</p>

                <div class="icon-links">
                    <a href="#" class="btn btn-kubo btn-kubo-alternate-second">HOME</a>
                    <a href="#" class="btn btn-kubo btn-kubo-alternate-second">GALLERY</a>
                </div>
            </div>
        </div>

@endsection
