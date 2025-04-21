@extends('layouts.app')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                Our Rooms
            </h2>
            <p class="mt-4 text-lg text-gray-500">
                Choose from our selection of comfortable accommodations
            </p>
        </div>

        <div class="mt-12 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($rooms as $room)
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="relative pb-3/4">
                    <img class="absolute inset-0 w-full h-full object-cover" src="{{ $room->image }}" alt="{{ $room->name }}">
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ $room->name }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ $room->description }}</p>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Max Guests: {{ $room->num_guest }}</p>
                        @if($room->amenities)
                        <p class="mt-2 text-sm text-gray-500">Amenities: {{ $room->amenities }}</p>
                        @endif
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <span class="text-lg font-medium text-gray-900">${{ $room->price }}/night</span>
                        <a href="{{ route('rooms.show', $room->id) }}" class="text-indigo-600 hover:text-indigo-500">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
