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
    .date-picker-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: none;
    }
    .date-picker-backdrop.show {
        display: block;
    }
    body.date-picker-open {
        overflow: hidden;
    }
    .date-picker-container {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        display: none;
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        width: 90%;
        max-width: 400px;
        margin: 0;
        border: 1px solid #e0e0e0;
        animation: fadeInScale 0.3s ease-out;
    }
    .date-picker-container.show {
        display: block;
    }
    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }
    .date-picker-container input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--secondary-color);
        border-radius: 30px;
        color: var(--secondary-color);
        font-family: var(--font-family);
        font-size: 16px;
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
    .btn-close {
        position: absolute;
        top: 10px;
        right: 15px;
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    .btn-close:hover {
        background-color: #f0f0f0;
        color: #333;
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
                    <div class="button-container  text-center">
                        <div class="date-picker-backdrop" id="datePickerBackdrop"></div>
                        <div class="date-picker-container" id="datePickerContainer">
                            <button type="button" class="btn-close" id="closeDatePicker">&times;</button>
                            <input type="text" id="dateRange" class="form-control" placeholder="Select dates" readonly>
                        </div>
                        <button class="btn btn-kubo-alternate" id="checkDatesBtn">Check Available Dates</button>
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
        const datePickerContainer = document.getElementById('datePickerContainer');
        const datePickerBackdrop = document.getElementById('datePickerBackdrop');
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
            }
        }

        // Initialize Flatpickr
        let fp = null;

        function initializeDatePicker() {
            // Clear any existing instance
            if (fp) {
                fp.destroy();
            }

            // Set initial dates if they exist in URL
            let defaultDates = [];
            if (checkIn && checkOut) {
                defaultDates = [checkIn, checkOut];
                checkDatesBtn.textContent = `${formatDate(new Date(checkIn))} - ${formatDate(new Date(checkOut))}`;
                updateBookNowButton(new Date(checkIn), new Date(checkOut));
            }

            // Initialize Flatpickr
            fp = flatpickr(dateRangeInput, {
                mode: "range",
                dateFormat: "Y-m-d",
                minDate: "today",
                disableMobile: "true",
                theme: "material_blue",
                defaultDate: defaultDates,
                disable: bookedDates,
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Add visual indicator for blocked dates
                    if (bookedDates.includes(dayElem.dateObj.toISOString().split('T')[0])) {
                        dayElem.classList.add('blocked');
                    }
                },
                onSelect: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 1) {
                        const newUrl = window.location.pathname +
                            `?check_in=${formatDateToYYYYMMDD(selectedDates[0])}`;
                        window.history.pushState({}, '', newUrl);
                    }
                },
                onChange: function(selectedDates, dateStr, instance) {
                    if (selectedDates.length === 2) {
                        const startDate = selectedDates[0];
                        const endDate = selectedDates[1];

                        // Check if selection overlaps with booked dates
                        // We need to check each date in the range against booked dates
                        let hasOverlap = false;
                        let currentDate = new Date(startDate);
                        
                        while (currentDate <= endDate) {
                            const dateStr = currentDate.toISOString().split('T')[0];
                            if (bookedDates.includes(dateStr)) {
                                hasOverlap = true;
                                break;
                            }
                            currentDate.setDate(currentDate.getDate() + 1);
                        }

                        if (hasOverlap) {
                            instance.clear();
                            checkDatesBtn.textContent = originalButtonText;
                            if (bookNowBtn) {
                                const baseUrl = bookNowBtn.getAttribute('href').split('?')[0];
                                bookNowBtn.setAttribute('href', baseUrl);
                            }

                            const newUrl = window.location.pathname;
                            window.history.pushState({}, '', newUrl);
                            return;
                        }

                        checkDatesBtn.textContent = `${formatDate(startDate)} - ${formatDate(endDate)}`;
                        updateBookNowButton(startDate, endDate);

                        const newUrl = window.location.pathname +
                            `?check_in=${formatDateToYYYYMMDD(startDate)}&check_out=${formatDateToYYYYMMDD(endDate)}`;
                        window.history.pushState({}, '', newUrl);

                        datePickerContainer.classList.remove('show');
                        datePickerBackdrop.classList.remove('show');
                        document.body.classList.remove('date-picker-open');
                    } else if (selectedDates.length === 0) {
                        checkDatesBtn.textContent = originalButtonText;
                        if (bookNowBtn) {
                            const baseUrl = bookNowBtn.getAttribute('href').split('?')[0];
                            bookNowBtn.setAttribute('href', baseUrl);
                        }

                        const newUrl = window.location.pathname;
                        window.history.pushState({}, '', newUrl);
                    }
                }
            });

            // Toggle date picker container
            checkDatesBtn.addEventListener('click', function() {
                datePickerContainer.classList.toggle('show');
                datePickerBackdrop.classList.toggle('show');
                document.body.classList.toggle('date-picker-open');
            });

            // Close date picker when clicking on backdrop
            datePickerBackdrop.addEventListener('click', function() {
                datePickerContainer.classList.remove('show');
                datePickerBackdrop.classList.remove('show');
                document.body.classList.remove('date-picker-open');
            });

            // Close date picker when clicking close button
            document.getElementById('closeDatePicker').addEventListener('click', function() {
                datePickerContainer.classList.remove('show');
                datePickerBackdrop.classList.remove('show');
                document.body.classList.remove('date-picker-open');
            });

            // Close date picker when clicking outside
            document.addEventListener('click', function(event) {
                if (!datePickerContainer.contains(event.target) && !checkDatesBtn.contains(event.target) && !datePickerBackdrop.contains(event.target)) {
                    datePickerContainer.classList.remove('show');
                    datePickerBackdrop.classList.remove('show');
                    document.body.classList.remove('date-picker-open');
                }
            });
        }

        // Fetch booked dates and initialize date picker
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
                    
                    // Check each date in the range against booked dates
                    let hasOverlap = false;
                    let currentDate = new Date(startDate);
                    
                    while (currentDate <= endDate) {
                        const dateStr = currentDate.toISOString().split('T')[0];
                        if (bookedDates.includes(dateStr)) {
                            hasOverlap = true;
                            break;
                        }
                        currentDate.setDate(currentDate.getDate() + 1);
                    }

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
    });
</script>
@endsection
