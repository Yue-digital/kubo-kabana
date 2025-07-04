@extends('layouts.app')

@section('additional-css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
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
    /* Center Flatpickr calendar */
    .flatpickr-calendar {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        z-index: 9999 !important;
        width: 400px !important;
        font-size: 16px !important;
    }
    
    
    .flatpickr-calendar .flatpickr-month {
        height: 60px !important;
    }
    
    .flatpickr-calendar .flatpickr-current-month {
        font-size: 18px !important;
        padding: 15px 0 !important;
    }
    
    .flatpickr-calendar .flatpickr-weekdays {
        height: 50px !important;
    }
    
    .flatpickr-calendar .flatpickr-weekday {
        font-size: 16px !important;
        padding: 15px 0 !important;
    }
    
    .flatpickr-calendar .flatpickr-days {
        padding: 10px !important;
        width: 100% !important;
    }
    
    .flatpickr-calendar .flatpickr-day {
        height: 45px !important;
        line-height: 45px !important;
        font-size: 16px !important;
        margin: 2px !important;
    }
    
    .flatpickr-calendar .flatpickr-day.selected {
        background: #886455 !important;
        border-color: #886455 !important;
    }
    
    .flatpickr-calendar .flatpickr-day.inRange {
        background: rgba(136, 100, 85, 0.2) !important;
        border-color: rgba(136, 100, 85, 0.2) !important;
    }

    .dayContainer{
        max-width: 100%;
        width: 100%;
    }
    .flatpickr-rContainer{
        width: 100%;
    }
    /* Responsive design for mobile */
    @media (max-width: 768px) {
        .date-picker-container {
            width: 95%;
            max-width: 350px;
            padding: 15px;
        }
        .date-picker-container input {
            font-size: 14px;
            padding: 10px 12px;
        }
        .flatpickr-calendar {
            width: 90% !important;
            max-width: 350px !important;
        }
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
            <div class="mb-3 main-image-container" style="position: relative; background-image: url('{{ $room->galleryImages[0]->image_url }}'); background-size: cover; background-position: center;">



                <div class="d-flex room-images-gallery gap-2">
                    @foreach($room->galleryImages as $image)
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
                <h2 class="fw-bold mb-1">{{ $room->name }}</h2>
                {{-- <div class="mb-2 text-guest ">
                    <i class="fa fa-users"></i> {{ $room->min_guest }} - {{ $room->max_guest }} guests
                </div> --}}
                <p class="mb-3">
                    {{ $room->description }}
                </p>
                <div class="mb-3 badge-container">
                    @foreach(explode(',', $room->amenities) as $amenity)
                        <span class="badge bg-light kubo-badge me-1">
                            {{ trim($amenity) }}
                        </span>
                    @endforeach
                </div>
                <div class="d-grid mt-auto gap-2 mb-2">
                    <div class="button-container text-center">
                        <div class="row">
                            <div class="col-6">
                                <input type="text" id="checkInDate" class="form-control" placeholder="Check-in date" readonly style="cursor: pointer;">
                            </div>
                            <div class="col-6">
                                <input type="text" id="checkOutDate" class="form-control" placeholder="Check-out date" readonly style="cursor: pointer;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary flex-fill btn-kubo-alternate btn-secondary" data-bs-toggle="modal" data-bs-target="#seasonModal">Prices</button>
                    <a href="{{ route('payment.index', ['checkIn' => $checkIn, 'checkOut' => $checkOut]) }}" class="btn book-now-btn btn-kubo-alternate flex-fill">Book Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Season Modal -->
<div class="modal fade" id="seasonModal" tabindex="-1" aria-labelledby="seasonModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3" style="border-radius: 20px;">
      <div class="modal-header pb-0 border-0">
        <h2 class="modal-title w-100 text-center fw-bold" id="seasonModalLabel" style="color: #7B5A3A; letter-spacing: 1px;">
          @if(isset($room) && $room->is_peak_season)
            PEAK SEASON
            <div class="small text-muted">
              {{-- {{ \Carbon\Carbon::parse($room->peak_season_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($room->peak_season_end)->format('M d, Y') }} --}}
            </div>
          @else
            LEAN SEASON
            <div class="small text-muted">
              {{-- {{ \Carbon\Carbon::parse($room->lean_season_start)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($room->lean_season_end)->format('M d, Y') }} --}}
            </div>
          @endif
        </h2>
      </div>
      <div class="modal-body pt-0 text-center">
        <div class="text-start mt-4" style="color: #7B5A3A;">
          @if(isset($room) && $room->is_peak_season)
            <strong>Weekday rate (Monday – Friday)</strong><br>
            Php {{ number_format($room->peak_weekday_price) }}
            <ul>
              <li>Minimum of {{ $room->min_guest }} adult guests and maximum of {{ $room->max_guest }} adult guests.</li>
              <li>Maximum of {{ $room->max_child }} kids aged 0 - {{ $room->max_child_age }} years old. <span style="color: #b08a60;">(free of charge)</span></li>
              <li>If requested, additional beddings will cost Php {{ $room->additional_bedding_cost }}</li>
            </ul>
            <strong>Weekend rate (Saturday & Sunday)</strong><br>
            Php {{ number_format($room->peak_weekend_price) }}
            <ul>
              <li>Minimum of {{ $room->min_guest }} adult guests and maximum of {{ $room->max_guest }} adult guests.</li>
              <li>Maximum of {{ $room->max_child }} kids aged 0 - {{ $room->max_child_age }} years old. <span style="color: #b08a60;">(free of charge)</span></li>
              <li>If requested, additional beddings will cost Php {{ $room->additional_bedding_cost }}</li>
            </ul>
          @else
            <strong>Weekday rate (Monday – Friday)</strong><br>
            Php {{ number_format($room->lean_weekday_price) }}
            <ul>
              <li>Minimum of {{ $room->min_guest }} adult guests and maximum of {{ $room->max_guest }} adult guests.</li>
              <li>Maximum of {{ $room->max_child }} kids aged 0 - {{ $room->max_child_age }} years old. <span style="color: #b08a60;">(free of charge)</span></li>
              <li>If requested, additional beddings will cost Php {{ $room->additional_bedding_cost }}</li>
            </ul>
            <strong>Weekend rate (Saturday & Sunday)</strong><br>
            Php {{ number_format($room->lean_weekend_price) }}
            <ul>
              <li>Minimum of {{ $room->min_guest }} adult guests and maximum of {{ $room->max_guest }} adult guests.</li>
              <li>Maximum of {{ $room->max_child }} kids aged 0 - {{ $room->max_child_age }} years old. <span style="color: #b08a60;">(free of charge)</span></li>
              <li>If requested, additional beddings will cost Php {{ $room->additional_bedding_cost }}</li>
            </ul>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('additional-js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
        const checkInDateInput = document.getElementById('checkInDate');
        const checkOutDateInput = document.getElementById('checkOutDate');
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
            }
        }

        // Validate date range
        function validateDateRange(checkInDate, checkOutDate) {
            if (!checkInDate || !checkOutDate) {
                return false;
            }

            // Check if check-in date is a valid checkout date (end of a booking period)
            const checkInDateStr = checkInDate.toISOString().split('T')[0];
            let checkInDateIsValid = true;
            
            if (bookedDates.includes(checkInDateStr)) {
                // Check if check-in date is the end of a booking period (checkout date)
                const previousDay = new Date(checkInDate);
                previousDay.setDate(previousDay.getDate() - 1);
                const previousDayStr = previousDay.toISOString().split('T')[0];
                
                // If the previous day is also booked, then check-in date is part of a booking period
                // and not a valid checkout date
                if (bookedDates.includes(previousDayStr)) {
                    checkInDateIsValid = false;
                }
            }

            if (!checkInDateIsValid) {
                return false;
            }

            // Check if any date in the range (excluding checkout date) is booked
            let hasBookedDatesInRange = false;
            let currentDate = new Date(checkInDate);
            
            // Check each date in the range (excluding the checkout date)
            while (currentDate < checkOutDate) {
                const dateStr = currentDate.toISOString().split('T')[0];
                if (bookedDates.includes(dateStr)) {
                    hasBookedDatesInRange = true;
                    break;
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }

            // Check if checkout date conflicts with existing bookings
            const checkoutDateStr = checkOutDate.toISOString().split('T')[0];
            const nextDay = new Date(checkOutDate);
            nextDay.setDate(nextDay.getDate() + 1);
            const nextDayStr = nextDay.toISOString().split('T')[0];
            
            let checkoutDateConflicts = false;
            if (bookedDates.includes(nextDayStr)) {
                // Check if nextDay is the start of a booking period
                let checkDate = new Date(nextDay);
                let consecutiveBookedDays = 0;
                
                // Count consecutive booked days starting from nextDay
                while (bookedDates.includes(checkDate.toISOString().split('T')[0])) {
                    consecutiveBookedDays++;
                    checkDate.setDate(checkDate.getDate() + 1);
                }
                
                // If nextDay is the start of a booking, then checkout date conflicts
                // UNLESS the checkout date itself is also a check-in date (start of booking)
                if (consecutiveBookedDays > 0) {
                    // Check if checkout date is the start of a booking period
                    const previousDay = new Date(checkOutDate);
                    previousDay.setDate(previousDay.getDate() - 1);
                    const previousDayStr = previousDay.toISOString().split('T')[0];
                    
                    // If previous day is NOT booked, then checkout date is a check-in date
                    // and it's valid to check out on a check-in date
                    if (!bookedDates.includes(previousDayStr)) {
                        // This is a valid check-in date, so checkout is allowed
                        checkoutDateConflicts = false;
                    } else {
                        // This is part of an active booking period, so checkout conflicts
                        checkoutDateConflicts = true;
                    }
                }
            }

            return !hasBookedDatesInRange && !checkoutDateConflicts;
        }

        // Initialize Flatpickr instances
        let checkInPicker = null;
        let checkOutPicker = null;

        function initializeDatePickers() {
            // Clear any existing instances
            if (checkInPicker) {
                checkInPicker.destroy();
            }
            if (checkOutPicker) {
                checkOutPicker.destroy();
            }

            // Set initial dates if they exist in URL
            if (checkIn) {
                checkInDateInput.value = checkIn;
            }
            if (checkOut) {
                checkOutDateInput.value = checkOut;
            }

            // Initialize Check-in Date Picker
            checkInPicker = flatpickr(checkInDateInput, {
                mode: "single",
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: "true",
                theme: "material_blue",
                inline: false,
                appendTo: document.body,
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // For check-in dates, we can allow checkout dates (dates that are booked)
                    // but we'll still show them as visually different
                    if (bookedDates.includes(dayElem.dateObj.toISOString().split('T')[0])) {
                        dayElem.classList.add('blocked');
                        // Don't disable the date - allow it to be selected for check-in
                    }
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 1) {
                        const checkInDate = selectedDates[0];
                        
                        // Update check-out picker min date
                        if (checkOutPicker) {
                            checkOutPicker.set('minDate', checkInDate);
                        }
                        
                        // Validate if both dates are selected
                        const checkOutDate = checkOutDateInput.value ? new Date(checkOutDateInput.value) : null;
                        if (checkOutDate) {
                            if (validateDateRange(checkInDate, checkOutDate)) {
                                updateBookNowButton(checkInDate, checkOutDate);
                                
                                const newUrl = window.location.pathname +
                                    `?check_in=${formatDateToYYYYMMDD(checkInDate)}&check_out=${formatDateToYYYYMMDD(checkOutDate)}`;
                                window.history.pushState({}, '', newUrl);
                            } else {
                                checkOutDateInput.value = '';
                                if (bookNowBtn) {
                                    const baseUrl = bookNowBtn.getAttribute('href').split('?')[0];
                                    bookNowBtn.setAttribute('href', baseUrl);
                                }
                            }
                        }
                    }
                }
            });

            // Initialize Check-out Date Picker
            checkOutPicker = flatpickr(checkOutDateInput, {
                mode: "single",
                dateFormat: "Y-m-d",
                minDate: checkIn ? new Date(checkIn) : "today",
                disableMobile: "true",
                theme: "material_blue",
                inline: false,
                appendTo: document.body,
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // For check-out dates, we can allow check-in dates (dates that are booked)
                    // but we'll still show them as visually different
                    if (bookedDates.includes(dayElem.dateObj.toISOString().split('T')[0])) {
                        dayElem.classList.add('blocked');
                        // Don't disable the date - allow it to be selected for check-out
                    }
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 1) {
                        const checkOutDate = selectedDates[0];
                        const checkInDate = checkInDateInput.value ? new Date(checkInDateInput.value) : null;
                        
                        if (checkInDate) {
                            if (validateDateRange(checkInDate, checkOutDate)) {
                                updateBookNowButton(checkInDate, checkOutDate);
                                
                                const newUrl = window.location.pathname +
                                    `?check_in=${formatDateToYYYYMMDD(checkInDate)}&check_out=${formatDateToYYYYMMDD(checkOutDate)}`;
                                window.history.pushState({}, '', newUrl);
                            } else {
                                instance.clear();
                                if (bookNowBtn) {
                                    const baseUrl = bookNowBtn.getAttribute('href').split('?')[0];
                                    bookNowBtn.setAttribute('href', baseUrl);
                                }
                            }
                        }
                    }
                }
            });

            // Update Book Now button if both dates are set
            if (checkIn && checkOut) {
                const checkInDate = new Date(checkIn);
                const checkOutDate = new Date(checkOut);
                if (validateDateRange(checkInDate, checkOutDate)) {
                    updateBookNowButton(checkInDate, checkOutDate);
                }
            }
        }

        // Fetch booked dates and initialize date pickers
        let bookedDates = [];
        fetch('/rooms/booked-dates')
            .then(response => response.json())
            .then(dates => {
                bookedDates = dates;
                initializeDatePickers();
            })
            .catch(error => {
                console.error('Error fetching booked dates:', error);
                initializeDatePickers();
            });
    });
</script>
@endsection
