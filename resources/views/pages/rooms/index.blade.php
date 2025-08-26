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
    
    /* Half-day blocked styles - check-in dates (lower half blocked) */
    .flatpickr-calendar .flatpickr-day.half-day-checkin {
        background: linear-gradient(to bottom, #fff 0%, #fff 50%, #ffebee 50%, #ffebee 100%) !important;
        color: #c62828 !important;
        position: relative !important;
        cursor: pointer !important;
    }
    .flatpickr-calendar .flatpickr-day.half-day-checkin::after {
        content: '' !important;
        position: absolute !important;
        top: 50% !important;
        left: 0 !important;
        right: 0 !important;
        height: 1px !important;
        background-color: #c62828 !important;
        z-index: 1 !important;
    }
    .flatpickr-calendar .flatpickr-day.half-day-checkin:hover {
        background: linear-gradient(to bottom, #f5f5f5 0%, #f5f5f5 50%, #ffcdd2 50%, #ffcdd2 100%) !important;
    }
    
    /* Half-day blocked styles - check-out dates (upper half blocked) */
    .flatpickr-calendar .flatpickr-day.half-day-checkout {
        background: linear-gradient(to bottom, #ffebee 0%, #ffebee 50%, #fff 50%, #fff 100%) !important;
        color: #c62828 !important;
        position: relative !important;
        cursor: pointer !important;
    }
    .flatpickr-calendar .flatpickr-day.half-day-checkout::after {
        content: '' !important;
        position: absolute !important;
        top: 50% !important;
        left: 0 !important;
        right: 0 !important;
        height: 1px !important;
        background-color: #c62828 !important;
        z-index: 1 !important;
    }
    .flatpickr-calendar .flatpickr-day.half-day-checkout:hover {
        background: linear-gradient(to bottom, #ffcdd2 0%, #ffcdd2 50%, #f5f5f5 50%, #f5f5f5 100%) !important;
    }
    
    /* Completely blocked dates - both check-in and check-out */
    .flatpickr-calendar .flatpickr-day.completely-blocked {
        background: #ffebee !important;
        color: #c62828 !important;
        text-decoration: line-through !important;
        cursor: not-allowed !important;
        position: relative !important;
    }
    /* .flatpickr-calendar .flatpickr-day.completely-blocked::before {
        content: '✕' !important;
        position: absolute !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        font-size: 14px !important;
        font-weight: bold !important;
        color: #c62828 !important;
        z-index: 2 !important;
    } */
    .flatpickr-calendar .flatpickr-day.completely-blocked:hover {
        background: #ffebee !important;
    }
    /* Center Flatpickr calendar */
    .flatpickr-calendar {
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        z-index: 9999 !important;
        width: 330px !important;
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
            <strong>Weekday rate (Monday, Tuesday, Wednesday)</strong><br>
            Php {{ number_format($room->peak_weekday_price) }}
            <ul>
              <li>Minimum of {{ $room->min_guest }} adult guests and maximum of {{ $room->max_guest }} adult guests.</li>
              <li>Maximum of {{ $room->max_child }} kids aged 0 - {{ $room->max_child_age }} years old. <span style="color: #b08a60;">(free of charge)</span></li>
              <li>If requested, additional beddings will cost Php {{ $room->additional_bedding_cost }}</li>
            </ul>
            <strong>Weekend rate (Thursday, Friday, Saturday, Sunday)</strong><br>
            Php {{ number_format($room->peak_weekend_price) }}
            <ul>
              <li>Minimum of {{ $room->min_guest }} adult guests and maximum of {{ $room->max_guest }} adult guests.</li>
              <li>Maximum of {{ $room->max_child }} kids aged 0 - {{ $room->max_child_age }} years old. <span style="color: #b08a60;">(free of charge)</span></li>
              <li>If requested, additional beddings will cost Php {{ $room->additional_bedding_cost }}</li>
            </ul>
          @else
            <strong>Weekday rate (Monday, Tuesday, Wednesday)</strong><br>
            Php {{ number_format($room->lean_weekday_price) }}
            <ul>
              <li>Minimum of {{ $room->min_guest }} adult guests and maximum of {{ $room->max_guest }} adult guests.</li>
              <li>Maximum of {{ $room->max_child }} kids aged 0 - {{ $room->max_child_age }} years old. <span style="color: #b08a60;">(free of charge)</span></li>
              <li>If requested, additional beddings will cost Php {{ $room->additional_bedding_cost }}</li>
            </ul>
            <strong>Weekend rate (Thursday, Friday, Saturday, Sunday)</strong><br>
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

        // Validate date range with half-day blocking
        function validateDateRange(checkInDate, checkOutDate) {
            if (!checkInDate || !checkOutDate) {
                return false;
            }

            const checkInDateStr = checkInDate.toISOString().split('T')[0];
            const checkOutDateStr = checkOutDate.toISOString().split('T')[0];

            // Debug: Log validation for July 30-31
            if (checkInDateStr === '2025-07-30' || checkOutDateStr === '2025-07-30' || 
                checkInDateStr === '2025-07-31' || checkOutDateStr === '2025-07-31') {
                console.log('Validating July 30-31:', {
                    checkInDateStr,
                    checkOutDateStr,
                    checkInDateInCheckInDates: checkInDates.includes(checkInDateStr),
                    checkOutDateInCheckOutDates: checkOutDates.includes(checkOutDateStr),
                    checkInDateInBookedDates: bookedDates.includes(checkInDateStr),
                    checkOutDateInBookedDates: bookedDates.includes(checkOutDateStr)
                });
            }

            // Check if check-in date conflicts with existing check-ins (half-day blocking)
            // If someone is checking in on this date, we can't check in on the same date
            if (checkInDates.includes(checkInDateStr)) {
                console.log('Check-in date conflicts with existing check-in:', checkInDateStr);
                return false;
            }

            // Check if check-out date conflicts with existing check-outs (half-day blocking)
            // If someone is checking out on this date, we can't check out on the same date
            if (checkOutDates.includes(checkOutDateStr)) {
                console.log('Check-out date conflicts with existing check-out:', checkOutDateStr);
                return false;
            }

            // Check if any date in the range (excluding checkout date) is booked
            let hasBookedDatesInRange = false;
            let currentDate = new Date(checkInDate);
            
            // Check each date in the range (excluding the checkout date)
            while (currentDate < checkOutDate) {
                const dateStr = currentDate.toISOString().split('T')[0];
                if (bookedDates.includes(dateStr)) {
                    console.log('Found booked date in range:', dateStr);
                    hasBookedDatesInRange = true;
                    break;
                }
                currentDate.setDate(currentDate.getDate() + 1);
            }

            const isValid = !hasBookedDatesInRange;
            console.log('Date range validation result:', {
                checkInDateStr,
                checkOutDateStr,
                hasBookedDatesInRange,
                isValid
            });
            
            return isValid;
        }

        // Initialize Flatpickr instances
        let checkInPicker = null;
        let checkOutPicker = null;

        // Function to refresh calendar styling after data is loaded
        function refreshCalendarStyling() {
            if (checkInPicker) {
                // Force a redraw of the calendar to apply styling
                checkInPicker.redraw();
            }
            if (checkOutPicker) {
                // Force a redraw of the calendar to apply styling
                checkOutPicker.redraw();
            }
        }

        // Function to format date consistently for comparison
        function formatDateForComparison(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function initializeDatePickers() {
            // Initialize Check-in Date Picker
            checkInPicker = flatpickr(checkInDateInput, {
                dateFormat: "M d, Y",
                minDate: "today",
                disable: [
                    function(date) {
                        const dateStr = formatDateForComparison(date);
                        // Block fully booked dates AND check-in dates
                        // Also block dates that have both check-in and check-out
                        const isFullyBooked = bookedDates.includes(dateStr);
                        const hasCheckIn = checkInDates.includes(dateStr);
                        const hasCheckOut = checkOutDates.includes(dateStr);
                        
                        // If it has both check-in and check-out, block it completely
                        if (hasCheckIn && hasCheckOut) {
                            return true;
                        }
                        
                        // Block fully booked dates AND check-in dates
                        return isFullyBooked || hasCheckIn;
                    }
                ],
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        const selectedDate = selectedDates[0];
                        checkInDateInput.value = formatDate(selectedDate);
                        
                        // Reset check-out picker when check-in changes
                        if (checkOutPicker) {
                            checkOutPicker.clear();
                            checkOutDateInput.value = '';
                        }
                        
                        // Update Book Now button with check-in date only
                        updateBookNowButton(selectedDate, null);
                    }
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const dateStr = formatDateForComparison(dayElem.dateObj);
                    
                    // Debug: Log all dates being processed
                    console.log('Processing date in check-in onDayCreate:', {
                        dateStr,
                        dayElemText: dayElem.textContent,
                        checkInDates,
                        isInCheckInDates: checkInDates.includes(dateStr)
                    });
                    
                    // Debug: Log when checking for July 29
                    if (dateStr === '2025-07-29') {
                        console.log('Checking July 29 in onDayCreate:', {
                            dateStr,
                            checkInDates,
                            isInCheckInDates: checkInDates.includes(dateStr),
                            dayElem
                        });
                    }
                    
                    // Apply half-day blocking for check-in dates (lower half blocked)
                    if (checkInDates.includes(dateStr)) {
                        dayElem.classList.add('half-day-checkin');
                        dayElem.setAttribute('title', 'Check-out only');
                        console.log('Applied half-day-checkin to:', dateStr);
                        
                        // Debug: Check if class was actually added
                        setTimeout(() => {
                            if (dayElem.classList.contains('half-day-checkin')) {
                                console.log('✅ Class half-day-checkin successfully applied to:', dateStr);
                                console.log('Element classes:', dayElem.className);
                                console.log('Element computed styles:', window.getComputedStyle(dayElem).background);
                            } else {
                                console.log('❌ Class half-day-checkin NOT applied to:', dateStr);
                            }
                        }, 100);
                    }
                    
                    // Also show check-out dates (upper half blocked) in check-in picker for reference
                    if (checkOutDates.includes(dateStr)) {
                        dayElem.classList.add('half-day-checkout');
                        dayElem.setAttribute('title', 'Check-in only');
                        console.log('Applied half-day-checkout to check-in picker:', dateStr);
                    }
                    
                    // Apply completely blocked styling for dates with both check-in and check-out
                    if (checkInDates.includes(dateStr) && checkOutDates.includes(dateStr)) {
                        dayElem.classList.add('completely-blocked');
                        dayElem.setAttribute('title', 'Completely blocked - both check-in and check-out');
                        console.log('Applied completely-blocked to:', dateStr);
                    }
                }
            });

            // Initialize Check-out Date Picker
            checkOutPicker = flatpickr(checkOutDateInput, {
                dateFormat: "M d, Y",
                minDate: "today",
                disable: [
                    function(date) {
                        const dateStr = formatDateForComparison(date);
                        // Block fully booked dates AND check-out dates
                        // Also block dates that have both check-in and check-out
                        const isFullyBooked = bookedDates.includes(dateStr);
                        const hasCheckIn = checkInDates.includes(dateStr);
                        const hasCheckOut = checkOutDates.includes(dateStr);
                        
                        // If it has both check-in and check-out, block it completely
                        if (hasCheckIn && hasCheckOut) {
                            return true;
                        }
                        
                        // If it has a check-out, disable it
                        if (hasCheckOut) {
                            return true;
                        }
                        
                        // If it's fully booked but also has a check-in, allow it (can check out same day as check-in)
                        if (isFullyBooked && hasCheckIn) {
                            return false;
                        }
                        
                        // If it's fully booked without check-in, disable it
                        if (isFullyBooked) {
                            return true;
                        }
                        
                        // Otherwise allow it
                        return false;
                    }
                ],
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length > 0) {
                        const selectedDate = selectedDates[0];
                        checkOutDateInput.value = formatDate(selectedDate);
                        
                        // Get check-in date
                        const checkInDate = checkInPicker.selectedDates[0];
                        
                        if (checkInDate) {
                            // Update Book Now button with both dates
                            updateBookNowButton(checkInDate, selectedDate);
                        }
                    }
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    const dateStr = formatDateForComparison(dayElem.dateObj);
                    
                    // Debug: Log all dates being processed
                    console.log('Processing date in check-out onDayCreate:', {
                        dateStr,
                        dayElemText: dayElem.textContent,
                        checkOutDates,
                        isInCheckOutDates: checkOutDates.includes(dateStr)
                    });
                    
                    // Apply half-day blocking for check-out dates (upper half blocked)
                    if (checkOutDates.includes(dateStr)) {
                        dayElem.classList.add('half-day-checkout');
                        dayElem.setAttribute('title', 'Check-out only - upper half blocked');
                        console.log('Applied half-day-checkout to:', dateStr);
                        
                        // Debug: Check if class was actually added
                        setTimeout(() => {
                            if (dayElem.classList.contains('half-day-checkout')) {
                                console.log('✅ Class half-day-checkout successfully applied to:', dateStr);
                                console.log('Element classes:', dayElem.className);
                                console.log('Element computed styles:', window.getComputedStyle(dayElem).background);
                            } else {
                                console.log('❌ Class half-day-checkout NOT applied to:', dateStr);
                            }
                        }, 100);
                    }
                    
                    // Also show check-in dates (lower half blocked) in check-out picker for reference
                    if (checkInDates.includes(dateStr)) {
                        dayElem.classList.add('half-day-checkin');
                        dayElem.setAttribute('title', 'Check-in only - lower half blocked');
                        console.log('Applied half-day-checkin to check-out picker:', dateStr);
                    }
                    
                    // Apply completely blocked styling for dates with both check-in and check-out
                    if (checkInDates.includes(dateStr) && checkOutDates.includes(dateStr)) {
                        dayElem.classList.add('completely-blocked');
                        dayElem.setAttribute('title', 'Completely blocked - both check-in and check-out');
                        console.log('Applied completely-blocked to check-out picker:', dateStr);
                    }
                }
            });

            // Set initial values if URL parameters exist
            if (checkIn) {
                const checkInDate = new Date(checkIn);
                if (!isNaN(checkInDate.getTime())) {
                    checkInPicker.setDate(checkInDate);
                    checkInDateInput.value = formatDate(checkInDate);
                    
                    // Update Book Now button with check-in date
                    updateBookNowButton(checkInDate, null);
                }
            }
            
            if (checkOut) {
                const checkOutDate = new Date(checkOut);
                if (!isNaN(checkOutDate.getTime())) {
                    checkOutPicker.setDate(checkOutDate);
                    checkOutDateInput.value = formatDate(checkOutDate);
                    
                    // Update Book Now button with both dates if check-in is also set
                    if (checkInPicker.selectedDates[0]) {
                        updateBookNowButton(checkInPicker.selectedDates[0], checkOutDate);
                    }
                }
            }
        }

        // Fetch booked dates and initialize date pickers
        let bookedDates = [];
        let checkInDates = [];
        let checkOutDates = [];
        
        fetch('/rooms/booked-dates')
            .then(response => response.json())
            .then(data => {
                // Handle both old format (array) and new format (object)
                if (Array.isArray(data)) {
                    bookedDates = data;
                } else {
                    bookedDates = data.booked_dates || [];
                    checkInDates = data.check_in_dates || [];
                    checkOutDates = data.check_out_dates || [];
                }

                console.log(checkInDates,'checkInDates');
                console.log(checkOutDates,'checkOutDates');
                console.log(bookedDates,'bookedDates');
                
                // Debug: Check if July 30-31 has any special logic
                console.log('July 30 in checkOutDates:', checkOutDates.includes('2025-07-30'));
                console.log('July 31 in checkOutDates:', checkOutDates.includes('2025-07-31'));
                console.log('July 30 in bookedDates:', bookedDates.includes('2025-07-30'));
                console.log('July 31 in bookedDates:', bookedDates.includes('2025-07-31'));
                
                // Debug: Check July 29 specifically
                console.log('July 29 in checkInDates:', checkInDates.includes('2025-07-29'));
                console.log('July 29 in checkOutDates:', checkOutDates.includes('2025-07-29'));
                console.log('July 29 in bookedDates:', bookedDates.includes('2025-07-29'));
                
                initializeDatePickers();
                refreshCalendarStyling(); // Call this after data is loaded to apply styling
            })
            .catch(error => {
                console.error('Error fetching booked dates:', error);
                initializeDatePickers();
                refreshCalendarStyling(); // Call this even on error to ensure styling is applied
            });
    });
</script>
@endsection
