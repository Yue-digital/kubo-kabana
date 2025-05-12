@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-kubo text-white">
                    <h3 class="mb-0">Make a Reservation</h3>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function calculateTotal() {
        const kuboPrice = 1000;
        
        const checkIn = new Date(document.getElementById('check_in').value);
        const checkOut = new Date(document.getElementById('check_out').value);
        const numberOfKubos = parseInt(document.getElementById('number_of_kubos').value);
        
        if (checkIn && checkOut) {
            const numberOfNights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            const total = numberOfKubos * kuboPrice * numberOfNights;
            document.getElementById('total_amount').textContent = `₱${total.toLocaleString()}`;
        }
    }

    document.getElementById('check_in').addEventListener('change', calculateTotal);
    document.getElementById('check_out').addEventListener('change', calculateTotal);
    document.getElementById('number_of_kubos').addEventListener('change', calculateTotal);
</script>
@endpush
@endsection 