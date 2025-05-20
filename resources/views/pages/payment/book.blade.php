@extends('pages.payment.index')
@section('payment_content')

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
<script>
document.addEventListener('DOMContentLoaded', function() {
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
