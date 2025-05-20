@extends('layouts.app')


@section('additional-css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
    <style>
        body{
            background-color: #219EBC;
        }
    </style>
@endsection

@section('additional-js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
@endsection

@section('content')

<div class="container-fluid gallery-container">
    <div class="row">
        <div class="col-md-12">
            <h1>Gallery</h1>
        </div>

        <div class="col-md-12 gallery-images">
            <a href="{{Storage::url('/pic1.png')}}" data-lightbox="gallery"><img src="{{Storage::url('/pic1.png')}}" alt=""></a>
            <a href="{{Storage::url('/pic2.png')}}" data-lightbox="gallery"><img src="{{Storage::url('/pic2.png')}}" alt=""></a>
            <a href="{{Storage::url('/pic3.png')}}" data-lightbox="gallery"><img src="{{Storage::url('/pic3.png')}}" alt=""></a>
            <a href="{{Storage::url('/pic4.png')}}" data-lightbox="gallery"><img src="{{Storage::url('/pic4.png')}}" alt=""></a>
            <a href="{{Storage::url('/pic5.png')}}" data-lightbox="gallery"><img src="{{Storage::url('/pic5.png')}}" alt=""></a>
            <a href="{{Storage::url('/pic6.png')}}" data-lightbox="gallery"><img src="{{Storage::url('/pic6.png')}}" alt=""></a>
            <a href="{{Storage::url('/pic7.png')}}" data-lightbox="gallery"><img src="{{Storage::url('/pic7.png')}}" alt=""></a>
            <a href="{{Storage::url('/pic8.png')}}" data-lightbox="gallery"><img src="{{Storage::url('/pic8.png')}}" alt=""></a>
        </div>


        <div class="col-md-12 gallery-button">
            <a href="{{ route('kubo-room') }}" class="btn btn-kubo">Check out our rooms!</a>
        </div>
    </div>
</div>
@endsection
