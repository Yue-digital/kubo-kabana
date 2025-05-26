@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <div class="position-relative vh-100">
        <!-- Video Background -->
        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden">
            <div class="position-absolute top-0 start-0 w-100 h-100">
                <video class="w-100 h-100 object-fit-cover" autoplay muted loop playsinline>
                    <source src="{{Storage::url('/hero-vid.mp4')}}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
        </div>

        <!-- Content -->
        <div class="position-relative container h-100 d-flex align-items-center justify-content-center">
            <div class="row">
                <div class="col-md-12">
                    <img src="{{Storage::url('/kubo-hero.png')}}" alt="">
                    </p>
                    <div class="mt-4 text-center">
                        <a href="{{ route('kubo-room') }}" class="btn btn-light btn-lg btn-kubo">
                            Barangay Beneg, Botalan, Zambales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Check Availability Section -->
    <div class="position-relative check-availability-container" >
        <div class="container">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <form action="{{ route('kubo-room') }}" method="GET" class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <label for="check_in" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="check_in" name="check_in" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="check_out" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="check_out" name="check_out" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-kubo w-100">Check Availability</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid contact-features-container">
        <div class="row contact-features" style="background-image: url('{{Storage::url('/contact-bg.png')}}');">

            <div class="col-md-6 contact-col">
                <img src="{{Storage::url('/full-logo.png')}}" class="full-logo" alt="" srcset="">

                <div class="contact">
                    <div class="phone">
                        <img class="kubo-icon" src="{{Storage::url('/phone.png')}}" alt="">
                        <a href="tel:+63915 723 41 64">+63915 723 41 64</a>
                        <a href="tel:+02403-52-24">Manila (02) 403-52-24</a>
                    </div>

                    <div class="email">
                        <img class="kubo-icon" src="{{Storage::url('/mail.png')}}" alt="">
                        <a href="mailto:eesanchez@kubokabana.com">eesanchez@kubokabana.com</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 feature-col">
                <div class="content">
                    <h2>FEATURES</h2>
                    <p>The resort  features 5 Kubos with balconies each. Equipped with 24/7 CCTV cameras, the resort ensures your safety and security while inside the premises. It also has a pavilion where guests can dine and relax while enjoying the ocean view. It also has Wi -Fi access for guests who want to surf the net.</p>

                    <a href="{{ route('services') }}" class="btn btn-lg btn-kubo">SERVICES</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid swiper-container">
        <div class="row">
            <div class="col-md-12 swiper-col">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @if($home && $home->galleryImages)
                            @foreach($home->galleryImages as $image)
                                <div class="swiper-slide">
                                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->caption ?? 'Gallery Image' }}" />
                                </div>
                            @endforeach
                        @else
                            <div class="swiper-slide">
                                <img src="{{Storage::url('/slider-1.png')}}" alt="Default Image" />
                            </div>
                        @endif
                    </div>

                    <!-- Navigation buttons -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>

            <div class="col-md-12 map-col position-relative">
                <div id="search-box" class="position-absolute top-0 start-50 translate-middle-x mt-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-map-marker-alt"></i>
                        </span>
                        <input id="origin-input" type="text" class="form-control border-start-0" placeholder="Enter your location">
                        <span class="input-group-text input-group-text-search border-start-0">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
                <div id="map" style="height: 700px"></div>
            </div>
        </div>
    </div>


@endsection


@section('additional-js')


<script>
    document.getElementById('check_in').addEventListener('change', function() {
        const startDate = this.value;
        const endDateInput = document.getElementById('check_out');
        endDateInput.min = startDate;
        
        // If end date is before start date, reset it
        if (endDateInput.value && endDateInput.value < startDate) {
            endDateInput.value = startDate;
        }
    });
</script>

@endsection