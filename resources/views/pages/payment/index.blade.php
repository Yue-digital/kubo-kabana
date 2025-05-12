@extends('layouts.app')

@section('content')

<div class="container-fluid payment-container position-relative" style="background-image: url('{{ Storage::url('payment-bg.png') }}'); ">
    <div class="row m-auto w-100 payment-row">

        @yield('payment_content')

    </div>
</div>

@endsection
