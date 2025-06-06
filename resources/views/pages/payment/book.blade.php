@extends('pages.payment.index')
@section('payment_content')

<!-- Add Flatpickr CSS in the head section -->
@section('additional-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .flatpickr-calendar {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 3px 13px rgba(0,0,0,0.08);
    }
    .flatpickr-day.blocked {
        background: #ffebee;
        color: #d32f2f;
        text-decoration: line-through;
        cursor: not-allowed;
    }
    .flatpickr-day.blocked:hover {
        background: #ffebee;
        color: #d32f2f;
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
                            <input type="date" class="form-control" id="check_in" name="check_in" value="{{ $checkIn ?? '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="check_out" class="form-label">Check-out Date</label>
                            <input type="date" class="form-control" id="check_out" name="check_out" value="{{ $checkOut ?? '' }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="num_guests" class="form-label">Number of Guests</label>
                            <input type="number" max="20" placeholder="Max of 20 guests" class="form-control" id="num_guests" name="num_guests" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="add_adult" class="form-label">Additional</label>
                            <input type="number" placeholder="Adults" class="form-control" id="add_adult" name="add_adult" required>
                        </div>

                        <div class="col-md-6 d-flex flex-column">
                            <label for="add_child" class="form-label"> </label>
                            <input type="number" placeholder="Child" class="form-control mt-auto" id="add_child" name="add_child" required>
                        </div>

                        <div class="col-md-6">
                            <label for="pet" class="form-label"> </label>
                            <input type="number" placeholder="Pets" class="form-control" id="pet" name="pet" required>
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

        // Calculate total including pets
        const total = baseGuests + totalAdditional + pets;

        if (total > 30) {
            alert('Total capacity limit: 30 people maximum.\n\nCurrent breakdown:\n- Base guests: ' + baseGuests + '\n- Additional guests: ' + totalAdditional + '\n- Pets: ' + pets + '\n\nTotal: ' + total);
            // Reset the last changed input to maintain the total under 30
            const lastChanged = document.activeElement;
            if (lastChanged) {
                lastChanged.value = Math.max(0, 30 - (total - parseInt(lastChanged.value)));
            }
        }

        return total;
    }

    // Add event listeners for additional adults and children
    [addAdultInput, addChildInput].forEach(input => {
        input.addEventListener('input', calculateTotalGuests);
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
    petInput.addEventListener('input', calculateTotalGuests);
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
        if (value > 20) {
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

    // Fetch booked dates
    fetch('/rooms/booked-dates')
        .then(response => response.json())
        .then(bookedDates => {
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');

            // Set min date to tomorrow
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowFormatted = tomorrow.toISOString().split('T')[0];

            // Initialize Flatpickr for check-in
            const checkInPicker = flatpickr(checkInInput, {
                minDate: tomorrowFormatted,
                dateFormat: "Y-m-d",
                disable: bookedDates,
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates.length > 0) {
                        // Update check-out min date to the selected check-in date
                        checkOutPicker.set('minDate', selectedDates[0]);

                        // If check-out date is before check-in date, update it
                        if (checkOutPicker.selectedDates[0] && checkOutPicker.selectedDates[0] < selectedDates[0]) {
                            checkOutPicker.setDate(selectedDates[0]);
                        }

                        // Calculate and validate the date range
                        validateDateRange(selectedDates[0], checkOutPicker.selectedDates[0]);
                    }
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Add visual indicator for blocked dates
                    if (bookedDates.includes(dayElem.dateObj.toISOString().split('T')[0])) {
                        dayElem.classList.add('blocked');
                    }
                }
            });

            // Initialize Flatpickr for check-out
            const checkOutPicker = flatpickr(checkOutInput, {
                minDate: tomorrowFormatted,
                dateFormat: "Y-m-d",
                disable: bookedDates,
                onChange: function(selectedDates, dateStr) {
                    if (selectedDates.length > 0) {
                        const checkInDate = new Date(checkInInput.value);
                        const checkOutDate = selectedDates[0];

                        // Ensure check-out date is not before check-in date
                        if (checkOutDate < checkInDate) {
                            checkOutPicker.setDate(checkInDate);
                            return;
                        }

                        // Calculate and validate the date range
                        validateDateRange(checkInDate, checkOutDate);
                    }
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Add visual indicator for blocked dates
                    if (bookedDates.includes(dayElem.dateObj.toISOString().split('T')[0])) {
                        dayElem.classList.add('blocked');
                    }
                }
            });

            // Function to validate date range and calculate duration
            function validateDateRange(checkIn, checkOut) {
                if (!checkIn || !checkOut) return;

                // Calculate duration in days
                const duration = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));

                // Check if any date in the range is booked
                let currentDate = new Date(checkIn);
                let hasBookedDates = false;

                while (currentDate <= checkOut) {
                    const dateStr = currentDate.toISOString().split('T')[0];
                    if (bookedDates.includes(dateStr)) {
                        hasBookedDates = true;
                        break;
                    }
                    currentDate.setDate(currentDate.getDate() + 1);
                }

                if (hasBookedDates) {
                    // Find next available date
                    let nextDate = new Date(checkOut);
                    nextDate.setDate(nextDate.getDate() + 1);
                    while (bookedDates.includes(nextDate.toISOString().split('T')[0])) {
                        nextDate.setDate(nextDate.getDate() + 1);
                    }
                    checkOutPicker.setDate(nextDate);
                    alert('Selected date range includes booked dates. End date has been adjusted.');
                    return;
                }

                // Validate minimum stay (if needed)
                if (duration < 1) {
                    alert('Check-out date must be after check-in date');
                    checkOutPicker.setDate(new Date(checkIn.getTime() + 24 * 60 * 60 * 1000));
                    return;
                }

                // Validate maximum stay (if needed)
                const MAX_STAY_DAYS = 30; // Adjust this value as needed
                if (duration > MAX_STAY_DAYS) {
                    alert(`Maximum stay duration is ${MAX_STAY_DAYS} days`);
                    const maxDate = new Date(checkIn);
                    maxDate.setDate(maxDate.getDate() + MAX_STAY_DAYS);
                    checkOutPicker.setDate(maxDate);
                    return;
                }

                // If all validations pass, you can trigger any additional calculations here
                // For example, calculating the total price based on duration
                calculateTotalPrice(duration);
            }

            // Function to calculate total price based on duration
            function calculateTotalPrice(duration) {
                // Get the base price and costs from the room data
                const basePrice = {{ $rooms->lean_weekday_price ?? 0 }}; // Use the base room price
                const costPerAdult = {{ $rooms->cost_adult ?? 0 }};
                const costPerChild = {{ $rooms->cost_child ?? 0 }};
                const costPerPet = {{ $rooms->cost_pet ?? 0 }};

                // Get the number of additional guests
                const additionalAdults = parseInt(addAdultInput.value) || 0;
                const children = parseInt(addChildInput.value) || 0;
                const pets = parseInt(petInput.value) || 0;

                // Calculate base price for the entire stay
                const totalBasePrice = basePrice * duration;

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
                        const checkInDate = new Date(checkIn);
                        const checkOutDate = new Date(checkOut);
                        const duration = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
                        calculateTotalPrice(duration);
                    }
                });
            });

            // Also calculate price when dates change
            [checkInInput, checkOutInput].forEach(input => {
                input.addEventListener('change', function() {
                    if (checkInInput.value && checkOutInput.value) {
                        const checkInDate = new Date(checkInInput.value);
                        const checkOutDate = new Date(checkOutInput.value);
                        const duration = Math.ceil((checkOutDate - checkInDate) / (1000 * 60 * 60 * 24));
                        calculateTotalPrice(duration);
                    }
                });
            });

            // Set initial min date for check-out based on check-in value if it exists
            if (checkInInput.value) {
                checkOutPicker.set('minDate', checkInInput.value);
            }
        })
        .catch(error => {
            console.error('Error fetching booked dates:', error);
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
        const basePrice = {{ $rooms->lean_weekday_price ?? 0 }};
        const duration = Math.ceil((new Date(document.getElementById('check_out').value) - new Date(document.getElementById('check_in').value)) / (1000 * 60 * 60 * 24));
        const totalBasePrice = basePrice * duration;
        
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
