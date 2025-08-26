@extends('pages.payment.index')
@section('payment_content')

<!-- Add Flatpickr CSS in the head section -->
@section('additional-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<style>
    .flatpickr-calendar {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 3px 13px rgba(0,0,0,0.08);
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
        .flatpickr-calendar {
            width: 90% !important;
            max-width: 350px !important;
        }
    }
    
    .policy-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px solid #dee2e6;
    }
    .policy-section h3 {
        color: #333;
        margin-bottom: 15px;
    }
    .policy-section ul {
        list-style-type: none;
        padding-left: 0;
    }
    .policy-section li {
        margin-bottom: 10px;
        padding-left: 20px;
        position: relative;
    }
    .policy-section li:before {
        content: "•";
        position: absolute;
        left: 0;
        color: #666;
    }
</style>
@endsection

<div class="col-md-12"> <h1 class="text-center">ACCOMODATION</h1></div>
        <div class="col-md-5"></div>
        <div class="col-md-6 payment-col">
            <div class="reservation-cont ">
                <form action="{{ route('payment.book') }}" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" placeholder="Type here..." class="form-control" id="name" name="name" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="contact_number" class="form-label">Contact Number</label>
                            <input type="tel" placeholder="Type here..." class="form-control" id="contact_number" name="phone" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" placeholder="Type here..." class="form-control" id="email" name="email" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="check_in" class="form-label">Check-in Date</label>
                            <input type="text" class="form-control" id="check_in" name="check_in" value="{{ $checkIn ?? '' }}" placeholder="Select check-in date" readonly style="cursor: pointer;" required>
                        </div>
                        <div class="col-md-6">
                            <label for="check_out" class="form-label">Check-out Date</label>
                            <input type="text" class="form-control" id="check_out" name="check_out" value="{{ $checkOut ?? '' }}" placeholder="Select check-out date" readonly style="cursor: pointer;" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="num_guests" class="form-label">Number of Guests</label>
                            <input type="number" min="0" max="20" placeholder="Max of 20 guests" class="form-control" id="num_guests" name="num_guests" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_adult" class="form-label">Additional</label>
                            <input type="number" min="0" placeholder="Adults" class="form-control" id="add_adult" name="add_adult" required>
                        </div>

                        <div class="col-md-6 d-flex flex-column">
                            <label for="add_child" class="form-label"> </label>
                            <input type="number" min="0" placeholder="Child" class="form-control mt-auto" id="add_child" name="add_child" required>
                        </div>

                        <div class="col-md-6">
                            <label for="pet" class="form-label"> </label>
                            <input type="number" min="0" placeholder="Pets" class="form-control" id="pet" name="pet" required>
                        </div>
                    </div>

                    <!-- Discount Code Section -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="discount_code" class="form-label">Discount Code</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="discount_code" name="discount_code" placeholder="Enter discount code" value="{{ $discountCode ?? '' }}">
                                <button type="button" class="btn btn-kubo btn-kubo-alternate" style="border-width: 0px;" id="applyDiscount">Apply</button>
                            </div>
                            <div id="discount_message" class="form-text"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h4 class="text-kubo mb-3">
                                @if(isset($rooms->discount_amount) && $rooms->discount_amount > 0)
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Original Price:</span>
                                        <span class="text-decoration-line-through">₱{{ number_format($rooms->original_price, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Discount:</span>
                                        <span class="text-success">-₱{{ number_format($rooms->discount_amount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Total Amount:</span>
                                    <span class="btn btn-kubo btn-kubo-alternate-second">₱{{ number_format($rooms->total_price ?? 0, 2) }}</span>
                                </div>
                            </h4>

                            
                            <input type="hidden" name="total_amount" id="total_amount_input" value="{{ $rooms->total_price ?? 0 }}">
                            <input type="hidden" name="original_amount" id="original_amount_input" value="{{ $rooms->original_price ?? $rooms->total_price ?? 0 }}">
                            <input type="hidden" name="discount_amount" id="discount_amount_input" value="{{ $rooms->discount_amount ?? 0 }}">
                        </div>
                    </div>

                    <!-- Booking Policy Section -->
                    <div class="policy-section">
                        <h3>Booking Policy</h3>
                        <div class="mb-3">
                            <strong>Non-Refundable</strong>
                        </div>
                        <div>
                            <strong>Rebooking Policy:</strong>
                            <ul>
                                <li>First 3 Months from Original Booking Date: Free of Charge (One Time Only)</li>
                                <li>Within 6 Months from Original Booking Date: Rebooking Fee of 25% of Total Night Rate</li>
                                <li>Within 9 Months from Original Booking Date: Rebooking Fee of 30% of Total Night Rate</li>
                                <li>Within 12 Months from Original Booking Date: Rebooking Fee of 35% of Total Night Rate</li>
                                <li>More than 1 Year from Original Booking Date: Not Allowed</li>
                            </ul>
                        </div>
                    </div>

                    <div class="payment-button">
                        <button type="submit" class="btn btn-kubo btn-kubo-alternate">BOOK NOW</button>
                    </div>
                </form>
            </div>
        </div>

@endsection

@section('additional-js')
<!-- Add Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add validation for number of guests
    const numGuestsInput = document.getElementById('num_guests');
    const addAdultInput = document.getElementById('add_adult');
    const addChildInput = document.getElementById('add_child');
    const petInput = document.getElementById('pet');

    // Function to calculate total guests
    function calculateTotalGuests() {
        const baseGuests = parseInt(numGuestsInput.value) || 0;
        const additionalAdults = parseInt(addAdultInput.value) || 0;
        const children = parseInt(addChildInput.value) || 0;
        const pets = parseInt(petInput.value) || 0;

        // Calculate total additional people (adults + children)
        const totalAdditional = additionalAdults + children;

        // Check if total additional people exceeds 10 (5 adults + 5 children)
        if (totalAdditional > 10) {
            alert('Additional guests limit: 10 people maximum (5 adults and 5 children combined).\nCurrent additional guests: ' + totalAdditional);
            // Reset the last changed input to maintain the total under 10
            const lastChanged = document.activeElement;
            if (lastChanged && (lastChanged.id === 'add_adult' || lastChanged.id === 'add_child')) {
                lastChanged.value = Math.max(0, 10 - (totalAdditional - parseInt(lastChanged.value)));
            }
        }

        return baseGuests + totalAdditional + pets;
    }

    // Add event listeners for additional adults and children
    [addAdultInput, addChildInput].forEach(input => {
        input.addEventListener('input', function() {
            // Prevent negative numbers
            if (parseInt(this.value) < 0) {
                this.value = 0;
            }
            calculateTotalGuests();
        });
        input.addEventListener('keypress', function(e) {
            const additionalAdults = parseInt(addAdultInput.value) || 0;
            const children = parseInt(addChildInput.value) || 0;
            const totalAdditional = additionalAdults + children;

            if (this.id === 'add_adult') {
                if (additionalAdults + parseInt(e.key) > 5) {
                    e.preventDefault();
                    alert('Additional adults limit: 5 maximum.\nCurrent additional adults: ' + additionalAdults);
                }
            } else if (this.id === 'add_child') {
                if (children + parseInt(e.key) > 5) {
                    e.preventDefault();
                    alert('Children limit: 5 maximum.\nCurrent children: ' + children);
                }
            }
        });
    });

    // Special handling for pets
    petInput.addEventListener('input', function() {
        // Prevent negative numbers
        if (parseInt(this.value) < 0) {
            this.value = 0;
        }
        calculateTotalGuests();
    });
    petInput.addEventListener('keypress', function(e) {
        const value = parseInt(this.value + e.key);
        if (value > 3) {
            e.preventDefault();
            alert('Pets limit: 3 maximum.\nCurrent pets: ' + this.value);
        }
    });

    // Special handling for base guests with max of 20
    numGuestsInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        // Prevent negative numbers
        if (value < 0) {
            this.value = 0;
        } else if (value > 20) {
            this.value = 20;
            alert('Base guests limit: 20 people maximum.\nCurrent base guests: ' + value);
        }
        calculateTotalGuests();
    });

    numGuestsInput.addEventListener('keypress', function(e) {
        const value = parseInt(this.value + e.key);
        if (value > 20) {
            e.preventDefault();
            alert('Base guests limit: 20 people maximum.\nCurrent base guests: ' + this.value);
        }
    });

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

    // Function to create a consistent date object without timezone issues
    function createLocalDate(dateString) {
        const date = new Date(dateString);
        return new Date(date.getFullYear(), date.getMonth(), date.getDate());
    }

    // Function to format date consistently for comparison
    function formatDateForComparison(date) {
        // Create a new date object to avoid timezone issues
        const localDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        const year = localDate.getFullYear();
        const month = String(localDate.getMonth() + 1).padStart(2, '0');
        const day = String(localDate.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Validate date range with half-day blocking
    function validateDateRange(checkInDate, checkOutDate) {
        if (!checkInDate || !checkOutDate) {
            return false;
        }

        const checkInDateStr = checkInDate.toISOString().split('T')[0];
        const checkOutDateStr = checkOutDate.toISOString().split('T')[0];

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
            
            initializeDatePickers();
            refreshCalendarStyling(); // Call this after data is loaded to apply styling
        })
        .catch(error => {
            console.error('Error fetching booked dates:', error);
            initializeDatePickers();
            refreshCalendarStyling(); // Call this even on error to ensure styling is applied
        });

    // Format date to YYYY-MM-DD without timezone issues
    function formatDateToYYYYMMDD(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function initializeDatePickers() {
        // Clear any existing instances
        if (checkInPicker) {
            checkInPicker.destroy();
        }
        if (checkOutPicker) {
            checkOutPicker.destroy();
        }

        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');

        // Set initial dates if they exist
        if (checkInInput.value) {
            checkInInput.value = checkInInput.value;
        }
        if (checkOutInput.value) {
            checkOutInput.value = checkOutInput.value;
        }

        // Initialize Check-in Date Picker
        checkInPicker = flatpickr(checkInInput, {
            dateFormat: "Y-m-d",
            minDate: "today",
            time_24hr: false,
            allowInput: false,
            clickOpens: true,
            parseDate: (datestr, format) => {
                return createLocalDate(datestr);
            },
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
                    
                    // Reset check-out picker when check-in changes
                    if (checkOutPicker) {
                        checkOutPicker.clear();
                        checkOutInput.value = '';
                    }
                    
                    // Update min date for check-out picker
                    checkOutPicker.set('minDate', createLocalDate(selectedDate));
                    
                    // Clear any existing price calculation
                    const priceContainer = document.querySelector('.text-kubo');
                    if (priceContainer) {
                        priceContainer.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Total Amount:</span>
                                <span class="btn btn-kubo btn-kubo-alternate-second">₱{{ number_format($rooms->total_price ?? 0, 2) }}</span>
                            </div>`;
                    }
                    
                    // Reset hidden input fields
                    document.getElementById('total_amount_input').value = '{{ $rooms->total_price ?? 0 }}';
                    document.getElementById('original_amount_input').value = '{{ $rooms->original_price ?? $rooms->total_price ?? 0 }}';
                    document.getElementById('discount_amount_input').value = '{{ $rooms->discount_amount ?? 0 }}';
                    
                    // Clear discount code
                    document.getElementById('discount_code').value = '';
                    document.getElementById('discount_message').innerHTML = '';
                }
            },
            onClear: function(selectedDates, dateStr, instance) {
                // Reset check-out picker when check-in is cleared
                if (checkOutPicker) {
                    checkOutPicker.clear();
                    checkOutInput.value = '';
                    checkOutPicker.set('minDate', 'today');
                }
                
                // Reset price display to default
                const priceContainer = document.querySelector('.text-kubo');
                if (priceContainer) {
                    priceContainer.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Total Amount:</span>
                            <span class="btn btn-kubo btn-kubo-alternate-second">₱{{ number_format($rooms->total_price ?? 0, 2) }}</span>
                        </div>`;
                }
                
                // Reset hidden input fields
                document.getElementById('total_amount_input').value = '{{ $rooms->total_price ?? 0 }}';
                document.getElementById('original_amount_input').value = '{{ $rooms->original_price ?? $rooms->total_price ?? 0 }}';
                document.getElementById('discount_amount_input').value = '{{ $rooms->discount_amount ?? 0 }}';
                
                // Clear discount code
                document.getElementById('discount_code').value = '';
                document.getElementById('discount_message').innerHTML = '';
            },
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const dateStr = formatDateForComparison(dayElem.dateObj);
                
                // Apply half-day blocking for check-in dates (lower half blocked)
                if (checkInDates.includes(dateStr)) {
                    dayElem.classList.add('half-day-checkin');
                    dayElem.setAttribute('title', 'Check-out only');
                    console.log('Applied half-day-checkin to:', dateStr);
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
        checkOutPicker = flatpickr(checkOutInput, {
            dateFormat: "Y-m-d",
            minDate: checkInInput.value ? createLocalDate(checkInInput.value) : "today",
            time_24hr: false,
            allowInput: false,
            clickOpens: true,
            parseDate: (datestr, format) => {
                return createLocalDate(datestr);
            },
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
                    const checkOutDate = selectedDates[0];
                    const checkInDate = checkInInput.value ? new Date(checkInInput.value) : null;
                    
                    if (checkInDate) {
                        if (validateDateRange(checkInDate, checkOutDate)) {
                            // Calculate and update price
                            const duration = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
                            calculateTotalPrice(duration);
                        } else {
                            // Invalid range - clear the selection
                            checkOutPicker.clear();
                            checkOutInput.value = '';
                            alert('Selected dates are not available. Please choose different dates.');
                        }
                    }
                }
            },
            onClear: function(selectedDates, dateStr, instance) {
                // Reset price display to default when check-out is cleared
                const priceContainer = document.querySelector('.text-kubo');
                if (priceContainer) {
                    priceContainer.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Total Amount:</span>
                            <span class="btn btn-kubo btn-kubo-alternate-second">₱{{ number_format($rooms->total_price ?? 0, 2) }}</span>
                        </div>`;
                }
                
                // Reset hidden input fields
                document.getElementById('total_amount_input').value = '{{ $rooms->total_price ?? 0 }}';
                document.getElementById('original_amount_input').value = '{{ $rooms->original_price ?? $rooms->total_price ?? 0 }}';
                document.getElementById('discount_amount_input').value = '{{ $rooms->discount_amount ?? 0 }}';
                
                // Clear discount code
                document.getElementById('discount_code').value = '';
                document.getElementById('discount_message').innerHTML = '';
            },
            onDayCreate: function(dObj, dStr, fp, dayElem) {
                const dateStr = formatDateForComparison(dayElem.dateObj);
                
                // Apply half-day blocking for check-out dates (upper half blocked)
                if (checkOutDates.includes(dateStr)) {
                    dayElem.classList.add('half-day-checkout');
                    dayElem.setAttribute('title', 'Check-out only - upper half blocked');
                    console.log('Applied half-day-checkout to:', dateStr);
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

        // Set initial min date for check-out based on check-in value if it exists
        if (checkInInput.value) {
            checkOutPicker.set('minDate', createLocalDate(checkInInput.value));
        }
    }

    // Function to calculate total price based on duration
    function calculateTotalPrice(duration) {
        // Get all the price fields from the room data
        const leanWeekdayPrice = {{ $rooms->lean_weekday_price ?? 0 }};
        const leanWeekendPrice = {{ $rooms->lean_weekend_price ?? 0 }};
        const peakWeekdayPrice = {{ $rooms->peak_weekday_price ?? 0 }};
        const peakWeekendPrice = {{ $rooms->peak_weekend_price ?? 0 }};
        
        // Get season dates
        const peakSeasonStart = '{{ $rooms->peak_season_start ?? "" }}';
        const peakSeasonEnd = '{{ $rooms->peak_season_end ?? "" }}';
        const leanSeasonStart = '{{ $rooms->lean_season_start ?? "" }}';
        const leanSeasonEnd = '{{ $rooms->lean_season_end ?? "" }}';
        
        const costPerAdult = {{ $rooms->cost_adult ?? 0 }};
        const costPerChild = {{ $rooms->cost_child ?? 0 }};
        const costPerPet = {{ $rooms->cost_pet ?? 0 }};

        // Get the number of additional guests
        const additionalAdults = parseInt(addAdultInput.value) || 0;
        const children = parseInt(addChildInput.value) || 0;
        const pets = parseInt(petInput.value) || 0;

        // Get check-in and check-out dates
        const checkInDate = createLocalDate(document.getElementById('check_in').value);
        const checkOutDate = createLocalDate(document.getElementById('check_out').value);
        
        let totalBasePrice = 0;
        
        // Calculate price for each night
        let currentDate = new Date(checkInDate);
        while (currentDate < checkOutDate) {
            const dateStr = formatDateForComparison(currentDate);
            const dayOfWeek = currentDate.getDay();
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 4 || dayOfWeek === 5 || dayOfWeek === 6; // Sunday = 0, Thursday = 4, Friday = 5, Saturday = 6
            
            // Determine if this date is in peak season
            let isPeakSeason = false;
            if (peakSeasonStart && peakSeasonEnd) {
                const peakStart = createLocalDate(peakSeasonStart);
                const peakEnd = createLocalDate(peakSeasonEnd);
                
                // Handle case where peak season spans across new year
                if (peakStart > peakEnd) {
                    // Peak season spans across new year (e.g., Dec 15 to Jan 15)
                    isPeakSeason = currentDate >= peakStart || currentDate <= peakEnd;
                } else {
                    // Normal peak season within same year
                    isPeakSeason = currentDate >= peakStart && currentDate <= peakEnd;
                }
            }
            
            // Debug logging for season detection
            console.log(`Date: ${dateStr}, IsPeakSeason: ${isPeakSeason}`);
            
            // Determine the price for this night
            let nightPrice;
            if (isPeakSeason) {
                nightPrice = isWeekend ? peakWeekendPrice : peakWeekdayPrice;
            } else {
                nightPrice = isWeekend ? leanWeekendPrice : leanWeekdayPrice;
            }
            
            // Debug logging for price selection
            console.log(`Date: ${dateStr}, Season: ${isPeakSeason ? 'Peak' : 'Lean'}, Day: ${isWeekend ? 'Weekend' : 'Weekday'}, Price: ${nightPrice}`);
            
            totalBasePrice += nightPrice;
            
            // Move to next day
            currentDate.setDate(currentDate.getDate() + 1);
        }

        // Calculate additional costs per night
        const additionalAdultsCost = additionalAdults * costPerAdult * duration;
        const childrenCost = children * costPerChild * duration;
        const petsCost = pets * costPerPet * duration;

        // Calculate total price
        const totalPrice = totalBasePrice + additionalAdultsCost + childrenCost + petsCost;

        // Update the price display
        const priceContainer = document.querySelector('.text-kubo');
        if (priceContainer) {
            let priceHtml = `
                <div class="d-flex justify-content-between align-items-center">
                    <span>Base Price (${duration} nights):</span>
                    <span>₱${totalBasePrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                </div>`;

            if (additionalAdultsCost > 0) {
                priceHtml += `
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Additional Adults (${additionalAdults} × ₱${costPerAdult}/night):</span>
                        <span>₱${additionalAdultsCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>`;
            }

            if (childrenCost > 0) {
                priceHtml += `
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Children (${children} × ₱${costPerChild}/night):</span>
                        <span>₱${childrenCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>`;
            }

            if (petsCost > 0) {
                priceHtml += `
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pets (${pets} × ₱${costPerPet}/night):</span>
                        <span>₱${petsCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>`;
            }

            priceHtml += `
                <div class="d-flex justify-content-between align-items-center">
                    <span>Total Amount:</span>
                    <span class="btn btn-kubo btn-kubo-alternate-second">₱${totalPrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                </div>`;

            priceContainer.innerHTML = priceHtml;
        }

        // Update hidden input fields
        document.getElementById('total_amount_input').value = totalPrice;
        document.getElementById('original_amount_input').value = totalPrice;
    }

    // Add event listeners for price calculation
    [addAdultInput, addChildInput, petInput].forEach(input => {
        input.addEventListener('input', function() {
            const checkIn = document.getElementById('check_in').value;
            const checkOut = document.getElementById('check_out').value;
            if (checkIn && checkOut) {
                const checkInDate = createLocalDate(checkIn);
                const checkOutDate = createLocalDate(checkOut);
                const duration = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
                calculateTotalPrice(duration);
            }
        });
    });

    // Existing discount code functionality
    document.getElementById('applyDiscount').addEventListener('click', function() {
        const code = document.getElementById('discount_code').value;
        const amount = document.getElementById('original_amount_input').value;
        const discountMessage = document.getElementById('discount_message');
        const discountInput = document.getElementById('discount_code');

        if (!code) {
            discountMessage.innerHTML = '<span class="text-danger">Please enter a discount code</span>';
            return;
        }

        // Disable the input and button while validating
        discountInput.disabled = true;
        this.disabled = true;
        discountMessage.innerHTML = '<span class="text-info">Validating discount code...</span>';

        // Get the base price and additional costs
        // Calculate the actual base price using the same logic as calculateTotalPrice
        const leanWeekdayPrice = {{ $rooms->lean_weekday_price ?? 0 }};
        const leanWeekendPrice = {{ $rooms->lean_weekend_price ?? 0 }};
        const peakWeekdayPrice = {{ $rooms->peak_weekday_price ?? 0 }};
        const peakWeekendPrice = {{ $rooms->peak_weekend_price ?? 0 }};
        
        // Get season dates
        const peakSeasonStart = '{{ $rooms->peak_season_start ?? "" }}';
        const peakSeasonEnd = '{{ $rooms->peak_season_end ?? "" }}';
        
        const duration = Math.ceil((new Date(document.getElementById('check_out').value) - new Date(document.getElementById('check_in').value)) / (1000 * 60 * 60 * 24));
        
        // Calculate the actual base price for the selected dates
        let totalBasePrice = 0;
        const checkInDate = createLocalDate(document.getElementById('check_in').value);
        const checkOutDate = createLocalDate(document.getElementById('check_out').value);
        
        let currentDate = new Date(checkInDate);
        while (currentDate < checkOutDate) {
            const isWeekend = currentDate.getDay() === 0 || currentDate.getDay() === 4 || currentDate.getDay() === 5 || currentDate.getDay() === 6;
            
            // Determine if this date is in peak season
            let isPeakSeason = false;
            if (peakSeasonStart && peakSeasonEnd) {
                const peakStart = createLocalDate(peakSeasonStart);
                const peakEnd = createLocalDate(peakSeasonEnd);
                
                // Handle case where peak season spans across new year
                if (peakStart > peakEnd) {
                    isPeakSeason = currentDate >= peakStart || currentDate <= peakEnd;
                } else {
                    isPeakSeason = currentDate >= peakStart && currentDate <= peakEnd;
                }
            }
            
            // Determine the price for this night
            let nightPrice;
            if (isPeakSeason) {
                nightPrice = isWeekend ? peakWeekendPrice : peakWeekdayPrice;
            } else {
                nightPrice = isWeekend ? leanWeekendPrice : leanWeekdayPrice;
            }
            
            totalBasePrice += nightPrice;
            
            // Move to next day
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        // Calculate additional costs
        const additionalAdults = parseInt(addAdultInput.value) || 0;
        const children = parseInt(addChildInput.value) || 0;
        const pets = parseInt(petInput.value) || 0;
        const costPerAdult = {{ $rooms->cost_adult ?? 0 }};
        const costPerChild = {{ $rooms->cost_child ?? 0 }};
        const costPerPet = {{ $rooms->cost_pet ?? 0 }};
        
        const additionalAdultsCost = additionalAdults * costPerAdult * duration;
        const childrenCost = children * costPerChild * duration;
        const petsCost = pets * costPerPet * duration;
        const additionalCosts = additionalAdultsCost + childrenCost + petsCost;

        fetch('{{ route("validate.discount") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ code, amount: Number(totalBasePrice) }) // Only send base price for discount
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                discountMessage.innerHTML = '<span class="text-success">Discount applied successfully!</span>';

                // Calculate final amount by adding discounted base price and additional costs
                const discountedBasePrice = data.final_amount;
                const finalAmount = discountedBasePrice + additionalCosts;
                const discountAmount = totalBasePrice - discountedBasePrice;

                // Update the form with new amounts
                document.getElementById('total_amount_input').value = finalAmount;
                document.getElementById('discount_amount_input').value = discountAmount;

                // Update the displayed prices
                const priceContainer = document.querySelector('.text-kubo');
                
                // Create or update price display
                priceContainer.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Base Price (${duration} nights):</span>
                        <span class="text-decoration-line-through">₱${totalBasePrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Discounted Base Price:</span>
                        <span>₱${discountedBasePrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>`;

                if (additionalAdultsCost > 0) {
                    priceContainer.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Additional Adults (${additionalAdults} × ₱${costPerAdult}/night):</span>
                            <span>₱${additionalAdultsCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                        </div>`;
                }

                if (childrenCost > 0) {
                    priceContainer.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Children (${children} × ₱${costPerChild}/night):</span>
                            <span>₱${childrenCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                        </div>`;
                }

                if (petsCost > 0) {
                    priceContainer.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Pets (${pets} × ₱${costPerPet}/night):</span>
                            <span>₱${petsCost.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                        </div>`;
                }

                priceContainer.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Discount:</span>
                        <span class="text-success">-₱${discountAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Total Amount:</span>
                        <span class="btn btn-kubo btn-kubo-alternate-second">₱${finalAmount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>
                `;
            } else {
                discountMessage.innerHTML = '<span class="text-danger">' + data.message + '</span>';
            }
            // Re-enable the input and button
            discountInput.disabled = false;
            this.disabled = false;
        })
        .catch(error => {
            discountMessage.innerHTML = '<span class="text-danger">An error occurred. Please try again.</span>';
            // Re-enable the input and button
            discountInput.disabled = false;
            this.disabled = false;
        });
    });
});
</script>
@endsection