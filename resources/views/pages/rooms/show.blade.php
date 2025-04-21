@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8">
            <!-- Room Images -->
            <div class="space-y-4">
                <div class="aspect-w-3 aspect-h-2 rounded-lg overflow-hidden">
                    <img src="{{ $room->image }}" alt="{{ $room->name }}" class="object-cover">
                </div>
                @if($room->gallery)
                <div class="grid grid-cols-2 gap-4">
                    @foreach(explode(',', $room->gallery) as $image)
                    <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden">
                        <img src="{{ trim($image) }}" alt="{{ $room->name }}" class="object-cover">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Room Info -->
            <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $room->name }}</h1>
                <div class="mt-3">
                    <h2 class="sr-only">Room information</h2>
                    <p class="text-3xl text-gray-900">${{ $room->price }}/night</p>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900">Description</h3>
                    <div class="mt-4 space-y-6">
                        <p class="text-base text-gray-500">{{ $room->description }}</p>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900">Details</h3>
                    <div class="mt-4">
                        <ul class="list-disc pl-4 space-y-2 text-sm text-gray-500">
                            <li>Maximum Guests: {{ $room->num_guest }}</li>
                            @if($room->amenities)
                            <li>Amenities: {{ $room->amenities }}</li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('pay') }}" class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Book Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
