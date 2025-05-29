@extends('layouts.app')


@section('additional-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        body{
            background-color: white;
        }

        .navbar img{
            filter: brightness(0) saturate(100%) invert(13%) sepia(20%) saturate(4136%) hue-rotate(171deg) brightness(98%) contrast(98%);
        }
        body .navbar{
            .nav-link{
                color: #023047 !important;
            }
        }
        .services-swiper {
            overflow: hidden;
        }
    </style>
@endsection

@section('additional-js')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Swiper('.services-swiper', {
                slidesPerView: 1,
                spaceBetween: 30,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    768: { slidesPerView: 1 },
                    1024: { slidesPerView: 1 }
                }
            });
        });
    </script>
@endsection

@section('content')

<div class="container-fluid services-container">
    <div class="row m-0">
        <div class="col-md-12 services-content">
            <h1>Services</h1>

            <p>To ensure guest's relaxation and enjoyment, Kubo Kabana offers services that can make their stay quite memorable. Below are the services that the resort caters to its guests.</p>
        </div>



        <div class="services-swiper">
            <div class="col-md-12 services-card-container swiper-wrapper">
                <div class="row swiper-slide services-card-row">
                    <div class="col-md-5 service-image">
                        <img src="{{Storage::url('/DSC03184 1.png')}}" alt="">
                    </div>
                    <div class="col-md-5 services-card">
                        <h2>PAVILION</h2>
                        <p>The pavilion is the perfect spot to eat your deliciously prepared food. Take pleasure at the al fresco dining by the beachfront while enjoying the beautiful ocean views. </p>

                        <img src="{{Storage::url('/cutlery 1.png')}}" alt="">
                    </div>

                </div>

                <div class="row swiper-slide services-card-row reverse-row">
                    <div class="col-md-5 service-image">
                        <img src="{{Storage::url('/DSC03184 1.png')}}" alt="">
                    </div>
                    <div class="col-md-5 services-card">
                        <h2>PAVILION</h2>
                        <p>The pavilion is the perfect spot to eat your deliciously prepared food. Take pleasure at the al fresco dining by the beachfront while enjoying the beautiful ocean views. </p>

                        <img src="{{Storage::url('/cutlery 1.png')}}" alt="">
                    </div>

                </div>

                <div class="row swiper-slide services-card-row">
                    <div class="col-md-5 service-image">
                        <img src="{{Storage::url('/DSC03184 1.png')}}" alt="">
                    </div>
                    <div class="col-md-5 services-card">
                        <h2>PAVILION</h2>
                        <p>The pavilion is the perfect spot to eat your deliciously prepared food. Take pleasure at the al fresco dining by the beachfront while enjoying the beautiful ocean views. </p>

                        <img src="{{Storage::url('/cutlery 1.png')}}" alt="">
                    </div>

                </div>


            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>





    </div>
</div>
@endsection
