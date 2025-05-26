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
    
    numGuestsInput.addEventListener('input', function() {
        const value = parseInt(this.value);
        if (value > 20) {
            this.value = 20;
            alert('Maximum number of guests is 20');
        }
    });

    numGuestsInput.addEventListener('keypress', function(e) {
        const value = parseInt(this.value + e.key);
        if (value > 20) {
            e.preventDefault();
            alert('Maximum number of guests is 20');
        }
    });

    // Add validation for additional inputs (adults, children, pets)
    const additionalInputs = ['add_adult', 'add_child', 'pet'];
    
    additionalInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        
        input.addEventListener('input', function() {
            const value = parseInt(this.value);
            if (value > 5) {
                this.value = 5;
                alert(`Maximum number of ${inputId === 'add_adult' ? 'additional adults' : inputId === 'add_child' ? 'children' : 'pets'} is 5`);
            }
        });

        input.addEventListener('keypress', function(e) {
            const value = parseInt(this.value + e.key);
            if (value > 5) {
                e.preventDefault();
                alert(`Maximum number of ${inputId === 'add_adult' ? 'additional adults' : inputId === 'add_child' ? 'children' : 'pets'} is 5`);
            }
        });
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
                        
                        // Check if any date in the range is booked
                        let currentDate = new Date(checkInDate);
                        while (currentDate <= checkOutDate) {
                            const dateStr = currentDate.toISOString().split('T')[0];
                            if (bookedDates.includes(dateStr)) {
                                // Find next available date
                                let nextDate = new Date(currentDate);
                                nextDate.setDate(nextDate.getDate() + 1);
                                while (bookedDates.includes(nextDate.toISOString().split('T')[0])) {
                                    nextDate.setDate(nextDate.getDate() + 1);
                                }
                                checkOutPicker.setDate(nextDate);
                                alert('Selected date range includes booked dates. End date has been adjusted.');
                                break;
                            }
                            currentDate.setDate(currentDate.getDate() + 1);
                        }
                    }
                },
                onDayCreate: function(dObj, dStr, fp, dayElem) {
                    // Add visual indicator for blocked dates
                    if (bookedDates.includes(dayElem.dateObj.toISOString().split('T')[0])) {
                        dayElem.classList.add('blocked');
                    }
                }
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

        fetch('{{ route("validate.discount") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ code, amount: Number(amount) })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                discountMessage.innerHTML = '<span class="text-success">Discount applied successfully!</span>';
                
                // Update the form with new amounts
                document.getElementById('total_amount_input').value = data.final_amount;
                document.getElementById('discount_amount_input').value = data.discount_amount;
                
                // Update the displayed prices
                const priceContainer = document.querySelector('.text-kubo');
                const originalPrice = Number(amount);
                const discountAmount = data.discount_amount;
                const finalAmount = data.final_amount;

                // Create or update price display
                priceContainer.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Original Price:</span>
                        <span class="text-decoration-line-through">₱${originalPrice.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    </div>
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
