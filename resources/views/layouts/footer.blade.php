<footer class=" kubo-footer py-5">
    <div class="container-fluid">
        <div class="row">
            <!-- Logo Column -->
            <div class="col-md-3 mb-0 mb-md-0">
                <img src="{{Storage::url('/kubo-logo-footer.png')}}" alt="Kubo Kabana Logo" class="img-fluid mb-3 kubo-footer-logo" >
                <p class="mb-0"><b>Build</b> new memories.</p>
                <p class="mb-0"><b>Share</b> new memories.</p>
                <p class="mb-0"><b>Experience</b> new memories.</p>
            </div>

            <!-- Explore Column -->
            <div class="col-md-2 menu-col mb-4 mb-md-0">
                <h5 class="mb-3">Explore</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('home') }}" class=" text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="{{ route('kubo-room') }}" class=" text-decoration-none">Rooms</a></li>
                    <li class="mb-2"><a href="{{ route('services') }}" class=" text-decoration-none">Services</a></li>
                    <li class="mb-2"><a href="{{ route('gallery') }}" class=" text-decoration-none">Gallery</a></li>
                    <li class="mb-2"><a href="{{ url('about-us') }}" class=" text-decoration-none">About Us</a></li>
                </ul>
            </div>

            <!-- Contact Us Column -->
            <div class="col-md-2 menu-col mb-4 mb-md-0">
                <h5 class="mb-3">Contact Us</h5>
                <ul class="list-unstyled">
                    <li class="mb-2 d-flex align-items-center">
                        <i class="fas fa-map-marker-alt me-2 "></i>
                        <span class="">Barangay Beneg, Botalan, Zambales</span>
                    </li>
                    @if($home = \App\Models\Home::first())
                        @if($home->phone)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-phone me-2 "></i>
                                <a href="tel:{{ $home->phone }}" class="text-decoration-none">{{ $home->phone }}</a>
                            </li>
                        @endif
                        @if($home->landline)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-phone me-2 "></i>
                                <a href="tel:{{ $home->landline }}" class="text-decoration-none">{{ $home->landline }}</a>
                            </li>
                        @endif
                        @if($home->email)
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-envelope me-2 "></i>
                                <a href="mailto:{{ $home->email }}" class="text-decoration-none">{{ $home->email }}</a>
                            </li>
                        @endif
                    @endif
                </ul>
            </div>

            <!-- Social Icons Column -->
            <div class="col-md-2 menu-col">
                <h5 class="mb-3">Follow Us</h5>
                <div class="d-flex gap-3">
                    @if($home = \App\Models\Home::first())
                        @foreach(config('social.platforms') as $key => $platform)
                            @if($key !== 'email' && $key !== 'phone' && $key !== 'landline' && $home->$key)
                                <a href="{{ $home->$key }}" class="text-white fs-4" target="_blank" rel="noopener noreferrer">
                                    <i class="{{ $platform['icon'] }}"></i>
                                </a>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <hr class="my-4 bg-secondary">

        <div class="row">
            <div class="col-12 text-center">
                <p class="mb-0 ">Copyright &copy; {{ date('Y') }} Kubo Kabana Beach Resort. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>
{{ \TawkTo::widgetCode('https://tawk.to/chat/68517acf01479d190ef50aa0/1itv4feqf') }}
