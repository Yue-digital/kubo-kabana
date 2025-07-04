<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"
  />
  <!-- Flatpickr CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  @livewireStyles


    <!-- Custom CSS -->
    <style>
        /* Prevent image selection and dragging */
        img {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            -webkit-user-drag: none;
            -khtml-user-drag: none;
            -moz-user-drag: none;
            -o-user-drag: none;
            user-drag: none;
            pointer-events: none;
        }
        
        /* Prevent text selection on images */
        img::selection {
            background: transparent;
        }
        
        img::-moz-selection {
            background: transparent;
        }
        
        /* Additional protection for background images */
        * {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        /* Allow text selection for input fields and text areas */
        input, textarea, [contenteditable="true"] {
            -webkit-user-select: text;
            -moz-user-select: text;
            -ms-user-select: text;
            user-select: text;
        }
    </style>

    @yield('additional-css')

</head>
<body>
    <div class="min-vh-100 d-flex flex-column">
        @include('layouts.header')

        <!-- Page Content -->
        <main class="flex-grow-1">
            @yield('content')
        </main>

        @include('layouts.footer')
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <!-- Flatpickr JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    @yield('additional-js')
    @livewireScripts

    <script>
        // Image Protection Script
        (function() {
            // Disable right-click context menu
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                return false;
            });

            // Disable keyboard shortcuts for saving
            document.addEventListener('keydown', function(e) {
                // Prevent Ctrl+S (Save)
                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    return false;
                }
                
                // Prevent Ctrl+Shift+S (Save As)
                if (e.ctrlKey && e.shiftKey && e.key === 'S') {
                    e.preventDefault();
                    return false;
                }
                
                // Prevent Ctrl+U (View Source)
                if (e.ctrlKey && e.key === 'u') {
                    e.preventDefault();
                    return false;
                }
            });

            // Disable drag and drop for images
            document.addEventListener('dragstart', function(e) {
                if (e.target.tagName === 'IMG') {
                    e.preventDefault();
                    return false;
                }
            });

            // Disable copy for images
            document.addEventListener('copy', function(e) {
                if (e.target.tagName === 'IMG') {
                    e.preventDefault();
                    return false;
                }
            });

            // Additional protection for images
            document.addEventListener('DOMContentLoaded', function() {
                // Add protection to all existing images
                const images = document.querySelectorAll('img');
                images.forEach(function(img) {
                    img.addEventListener('contextmenu', function(e) {
                        e.preventDefault();
                        return false;
                    });
                    
                    img.addEventListener('dragstart', function(e) {
                        e.preventDefault();
                        return false;
                    });
                    
                    img.addEventListener('selectstart', function(e) {
                        e.preventDefault();
                        return false;
                    });
                });

                // Monitor for dynamically added images
                const observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        mutation.addedNodes.forEach(function(node) {
                            if (node.nodeType === 1) { // Element node
                                const newImages = node.querySelectorAll ? node.querySelectorAll('img') : [];
                                if (node.tagName === 'IMG') {
                                    newImages.push(node);
                                }
                                
                                newImages.forEach(function(img) {
                                    img.addEventListener('contextmenu', function(e) {
                                        e.preventDefault();
                                        return false;
                                    });
                                    
                                    img.addEventListener('dragstart', function(e) {
                                        e.preventDefault();
                                        return false;
                                    });
                                    
                                    img.addEventListener('selectstart', function(e) {
                                        e.preventDefault();
                                        return false;
                                    });
                                });
                            }
                        });
                    });
                });

                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            });

            // Disable print screen
            document.addEventListener('keyup', function(e) {
                if (e.key === 'PrintScreen') {
                    e.preventDefault();
                    return false;
                }
            });

        })();

        let map, directionsService, directionsRenderer;

        function initMap() {
            // Add a 2-second delay before initializing the map
            setTimeout(() => {
                const destination = { lat: 15.27995630433838, lng: 120.00453604999998 };

                map = new google.maps.Map(document.getElementById("map"), {
                    center: destination,
                    zoom: 18,
                    mapTypeId: "roadmap",
                    disableDefaultUI: true, // Disable all map UI
                    gestureHandling: "none", // Disable all gestures including zooming
                    zoomControl: false, // Explicitly disable zoom control
                    styles: [
                        { elementType: "geometry", stylers: [{ color: "#ebe3cd" }] },
                        { elementType: "labels.text.fill", stylers: [{ color: "#523735" }] },
                        { elementType: "labels.text.stroke", stylers: [{ color: "#f5f1e6" }] },
                        { featureType: "water", elementType: "geometry.fill", stylers: [{ color: "#b9d3c2" }] }
                    ]
                });

                // Set up services
                directionsService = new google.maps.DirectionsService();
                directionsRenderer = new google.maps.DirectionsRenderer({
                    map: map,
                    suppressMarkers: false,
                });

                // Set up autocomplete
                const originInput = document.getElementById("origin-input");
                if (originInput) {
                    const autocomplete = new google.maps.places.Autocomplete(originInput);
                    autocomplete.setFields(["geometry", "name"]);

                    autocomplete.addListener("place_changed", () => {
                        const place = autocomplete.getPlace();
                        if (!place.geometry || !place.geometry.location) {
                            alert("No location found.");
                            return;
                        }

                        calculateAndDisplayRoute(place.geometry.location, destination);
                    });
                }
            }, 2000); // 2-second delay
        }

        function calculateAndDisplayRoute(origin, destination) {
            directionsService.route(
                {
                    origin: origin,
                    destination: destination,
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (response, status) => {
                    if (status === "OK") {
                        directionsRenderer.setDirections(response);
                        
                        // Get route details
                        const route = response.routes[0];
                        const duration = route.legs[0].duration.text;
                        const distance = route.legs[0].distance.text;
                        
                        // Create or update the info panel
                        let infoPanel = document.getElementById('route-info');
                        if (!infoPanel) {
                            infoPanel = document.createElement('div');
                            infoPanel.id = 'route-info';
                            infoPanel.className = 'route-info-panel';
                            document.getElementById('map').parentNode.appendChild(infoPanel);
                        }
                        
                        // Display route information
                        infoPanel.innerHTML = `
                            <div class="route-info-content">
                                <div class="route-info-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Estimated Time: ${duration}</span>
                                </div>
                                <div class="route-info-item">
                                    <i class="fas fa-road"></i>
                                    <span>Distance: ${distance}</span>
                                </div>
                            </div>
                        `;
                    } else {
                        alert("Directions request failed due to " + status);
                    }
                }
            );
        }
    </script>

    <script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB1_oxw3virUdjwJPaVyuKnSZr4Zplq4SE&libraries=places&callback=initMap">
    </script>

    <script>
       const swiper = new Swiper(".swiper", {
            slidesPerView: 1,
            spaceBetween: 30,
            centeredSlides: true,
            loop: true,
            autoplay: false,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            effect: "coverflow",
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: false,
            },
            breakpoints: {
                // when window width is >= 640px
                640: {
                    slidesPerView: 1,
                },
                // when window width is >= 768px
                768: {
                    slidesPerView: 2,
                },
                // when window width is >= 1024px
                1024: {
                    slidesPerView: 2,
                }
            }
        });
    </script>
</body>
</html>
