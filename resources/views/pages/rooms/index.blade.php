@extends('layouts.app')

@section('additional-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<style>
    body{
        background-color: #886455;
    }
    .gallery-thumbnail {
        cursor: pointer;
        transition: opacity 0.3s;
    }
    .gallery-thumbnail:hover {
        opacity: 0.8;
    }
    .gallery-thumbnail.active {
        border: 2px solid #886455;
    }
    .main-image-container {
        position: relative;
        cursor: pointer;
    }
    .main-image-container::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2rem;
        color: white;
        text-shadow: 0 0 10px rgba(0,0,0,0.5);
        opacity: 0;
        transition: opacity 0.3s;
    }
    .main-image-container:hover::after {
        opacity: 1;
    }
    /* Date Picker Styles */
    .button-container {
        position: relative;
    }
    .date-picker-container {
        position: absolute;
        display: none;
        background: white;
        padding: 15px;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        width: 100%;
        max-width: 400px;
        left: 50%;
        transform: translateX(-50%);
        bottom: 100%;
        margin-bottom: 10px;
    }
    .date-picker-container.show {
        display: block;
    }
    .date-picker-container input {
        width: 100%;
        padding: 10px;
        border: 2px solid var(--secondary-color);
        border-radius: 30px;
        color: var(--secondary-color);
        font-family: var(--font-family);
    }
    .date-picker-container input:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(136, 100, 85, 0.2);
    }
    .date-picker-container::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-top: 10px solid white;
    }
    /* Blocked dates styles */
    .flatpickr-day.blocked {
        background-color: #ffebee;
        color: #c62828;
        text-decoration: line-through;
        cursor: not-allowed;
    }
    .flatpickr-day.blocked:hover {
        background-color: #ffebee;
    }
    /* Airbnb import styles */
    .airbnb-import {
        margin-top: 10px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    .airbnb-import input {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .airbnb-import button {
        width: 100%;
        padding: 8px;
        background: #FF5A5F;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .airbnb-import button:hover {
        background: #E31C5F;
    }
    .airbnb-import .error {
        color: #dc3545;
        margin-top: 5px;
        font-size: 0.9em;
    }
    .airbnb-import .success {
        color: #28a745;
        margin-top: 5px;
        font-size: 0.9em;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-5 room-details-container mt-5">

    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center text-white mb-5">ROOM</h1>
        </div>
    </div>
    <div class="row p-5 image-row justify-content-center">
        
        <!-- Room Image Section -->
        <div class="col-md-6 room-image-col">
            <div class="mb-3 main-image-container" style="position: relative; background-image: url('{{ $rooms->galleryImages[0]->image_url }}'); background-size: cover; background-position: center;">
                

                
                <div class="d-flex room-images-gallery gap-2">
                    @foreach($rooms->galleryImages as $image)
                        <a href="{{ $image->image_url }}" data-lightbox="room-gallery" data-title="Room Image">
                            <img src="{{ $image->image_url }}" class="img-thumbnail gallery-thumbnail" style="width: 80px;" alt="Gallery Image">
                        </a>
                    @endforeach
                </div>

            </div>

            
            
        </div>
        <!-- Room Info Section -->
        <div class="col-md-5 room-info-col">
            <div class="card d-flex shadow-sm">
                <h2 class="fw-bold mb-1">Buhay Kubo</h2>
                <div class="mb-2 text-guest ">
                    <i class="fa fa-users"></i> 4-5 guests
                </div>
                <p class="mb-3">
                    Kubo Kabana native air conditioned hut guest rooms is composed of two (2) double deck beds. A breath taking view of the beach from its balcony where you can enjoy your meal and drinks is a perfect blend of comfort and style.
                </p>
                <div class="mb-3 badge-container">
                    <span class="badge bg-light kubo-badge me-1">4 Beds</span>
                    <span class="badge bg-light kubo-badge me-1">1 Shower</span>
                    <span class="badge bg-light kubo-badge me-1">2 Outlets</span>
                    <span class="badge bg-light kubo-badge me-1">1 Aircon</span>
                    <span class="badge bg-light kubo-badge me-1">1 Toilet</span>
                    <span class="badge bg-light kubo-badge me-1">Ocean View</span>
                </div>
                <div class="d-grid mt-auto gap-2 mb-2">
                    <div class="button-container  text-center">
                        <div class="date-picker-container" id="datePickerContainer">
                            <input type="text" id="dateRange" class="form-control" placeholder="Select dates" readonly>
                            {{-- <div class="airbnb-import">
                                <input type="text" id="airbnbUrl" placeholder="Enter Airbnb listing URL" class="form-control">
                                <button id="importAirbnb" class="btn">Import Airbnb Calendar</button>
                                <div id="importStatus"></div>
                            </div> --}}
                        </div>
                        <button class="btn btn-kubo-alternate" id="checkDatesBtn">Check Available Dates</button>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary flex-fill btn-kubo-alternate btn-secondary">Prices</button>
                    <a href="{{ route('payment.index', ['checkIn' => $checkIn, 'checkOut' => $checkOut]) }}" class="btn book-now-btn btn-kubo-alternate flex-fill">Book Now</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additional-js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script>
    $(document).ready(function() {
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'albumLabel': 'Image %1 of %2',
            'fadeDuration': 300,
            'imageFadeDuration': 300
        });

        // Date Picker Implementation
        const datePickerContainer = document.getElementById('datePickerContainer');
        const checkDatesBtn = document.getElementById('checkDatesBtn');
        const dateRangeInput = document.getElementById('dateRange');
        const originalButtonText = checkDatesBtn.textContent;
        const bookNowBtn = document.querySelector('.book-now-btn');

        // Get URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const checkIn = urlParams.get('check_in') || urlParams.get('checkIn');
        const checkOut = urlParams.get('check_out') || urlParams.get('checkOut');

        // Format date to readable format
        function formatDate(date) {
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric',
                year: 'numeric'
            });
        }

        // Format date to YYYY-MM-DD without timezone issues
        function formatDateToYYYYMMDD(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        // Update Book Now button href
        function updateBookNowButton(startDate, endDate) {
            if (bookNowBtn) {
                const baseUrl = bookNowBtn.getAttribute('href').split('?')[0];
                const newHref = `${baseUrl}?checkIn=${formatDateToYYYYMMDD(startDate)}&checkOut=${formatDateToYYYYMMDD(endDate)}`;
                bookNowBtn.setAttribute('href', newHref);
                console.log('Updated Book Now button href:', newHref); // Debug log
            }
        }

        // Set initial button text and Book Now href if dates are in URL
        if (checkIn && checkOut) {
            const startDate = new Date(checkIn);
            const endDate = new Date(checkOut);
            checkDatesBtn.textContent = `${formatDate(startDate)} - ${formatDate(endDate)}`;
            updateBookNowButton(startDate, endDate);
        }

        // Fetch booked dates
        let bookedDates = [];
        fetch('/rooms/booked-dates')
            .then(response => response.json())
            .then(dates => {
                bookedDates = dates;
                initializeDatePicker();
                
                // Check if current selection overlaps with booked dates
                if (checkIn && checkOut) {
                    const startDate = new Date(checkIn);
                    const endDate = new Date(checkOut);
                    const hasOverlap = bookedDates.some(date => {
                        const bookedDate = new Date(date);
                        return bookedDate >= startDate && bookedDate <= endDate;
                    });

                    if (hasOverlap) {
                        // Reset the selection if there's an overlap
                        checkDatesBtn.textContent = originalButtonText;
                        if (bookNowBtn) {
                            const baseUrl = bookNowBtn.getAttribute('href').split('?')[0];
                            bookNowBtn.setAttribute('href', baseUrl);
                        }
                        
                        // Remove date parameters from URL
                        const newUrl = window.location.pathname;
                        window.history.pushState({}, '', newUrl);
                    }
                }
            })
            .catch(error => {
                console.error('Error fetching booked dates:', error);
                initializeDatePicker();
            });

        function initializeDatePicker() {
            // Initialize Flatpickr
            const fp = flatpickr(dateRangeInput, {
                mode: "range",
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: "true",
                theme: "material_blue",
                defaultDate: checkIn && checkOut ? [checkIn, checkOut] : undefined,
                disable: bookedDates,
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Add visual indicator for blocked dates
                    if (bookedDates.includes(dayElem.dateObj.toISOString().split('T')[0])) {
                        dayElem.classList.add('blocked');
                    }
                },
                onSelect: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 1) {
                        // Update URL with first date
                        const newUrl = window.location.pathname + 
                            `?check_in=${formatDateToYYYYMMDD(selectedDates[0])}`;
                        window.history.pushState({}, '', newUrl);
                    }
                },
                onChange: function(selectedDates, dateStr, instance) {
                    console.log('Date selection changed:', selectedDates); // Debug log
                    
                    if (selectedDates.length === 2) {
                        const startDate = selectedDates[0];
                        const endDate = selectedDates[1];
                        
                        // Check if selection overlaps with booked dates
                        const hasOverlap = bookedDates.some(date => {
                            const bookedDate = new Date(date);
                            return bookedDate >= startDate && bookedDate <= endDate;
                        });

                        if (hasOverlap) {
                            // Clear selection if there's an overlap
                            instance.clear();
                            checkDatesBtn.textContent = originalButtonText;
                            if (bookNowBtn) {
                                const baseUrl = bookNowBtn.getAttribute('href').split('?')[0];
                                bookNowBtn.setAttribute('href', baseUrl);
                            }
                            alert('Selected dates overlap with booked dates. Please choose different dates.');
                            
                            // Remove date parameters from URL
                            const newUrl = window.location.pathname;
                            window.history.pushState({}, '', newUrl);
                            return;
                        }
                        
                        // Update button text with selected dates
                        checkDatesBtn.textContent = `${formatDate(startDate)} - ${formatDate(endDate)}`;
                        
                        // Update the Book Now button with selected dates
                        updateBookNowButton(startDate, endDate);
                        
                        // Update URL parameters
                        const newUrl = window.location.pathname + 
                            `?check_in=${formatDateToYYYYMMDD(startDate)}&check_out=${formatDateToYYYYMMDD(endDate)}`;
                        window.history.pushState({}, '', newUrl);
                        
                        // Hide the date picker after selection
                        datePickerContainer.classList.remove('show');
                    } else if (selectedDates.length === 0) {
                        // Reset button text if dates are cleared
                        checkDatesBtn.textContent = originalButtonText;
                        if (bookNowBtn) {
                            const baseUrl = bookNowBtn.getAttribute('href').split('?')[0];
                            bookNowBtn.setAttribute('href', baseUrl);
                        }
                        
                        // Remove date parameters from URL
                        const newUrl = window.location.pathname;
                        window.history.pushState({}, '', newUrl);
                    }
                }
            });

            // Toggle date picker when clicking the button
            checkDatesBtn.addEventListener('click', () => {
                datePickerContainer.classList.toggle('show');
                if (datePickerContainer.classList.contains('show')) {
                    fp.open();
                }
            });

            // Close date picker when clicking outside
            document.addEventListener('click', (e) => {
                if (!datePickerContainer.contains(e.target) && !checkDatesBtn.contains(e.target)) {
                    datePickerContainer.classList.remove('show');
                }
            });
        }

        // Airbnb import functionality
        $('#importAirbnb').on('click', function() {
            const airbnbUrl = $('#airbnbUrl').val();
            const importStatus = $('#importStatus');
            
            if (!airbnbUrl) {
                importStatus.html('<div class="error">Please enter an Airbnb URL</div>');
                return;
            }

            importStatus.html('<div>Importing calendar...</div>');
            
            $.ajax({
                url: '/rooms/import-airbnb',
                method: 'POST',
                data: {
                    airbnb_url: airbnbUrl,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    importStatus.html(`<div class="success">${response.message}</div>`);
                    // Refresh the date picker to show new blocked dates
                    location.reload();
                },
                error: function(xhr) {
                    importStatus.html(`<div class="error">${xhr.responseJSON?.error || 'Failed to import calendar'}</div>`);
                }
            });
        });
    });
</script>
@endsection
