<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitySeeder extends Seeder
{
    public function run()
    {
        $amenities = [
            // Room Features
            [
                'name' => 'Air Conditioning',
                'icon' => 'fa-snowflake',
                'description' => 'Air conditioned room for your comfort'
            ],
            [
                'name' => 'Ceiling Fan',
                'icon' => 'fa-fan',
                'description' => 'Ceiling fan for additional air circulation'
            ],
            [
                'name' => 'Balcony',
                'icon' => 'fa-door-open',
                'description' => 'Private balcony with seating area'
            ],
            [
                'name' => 'Ocean View',
                'icon' => 'fa-water',
                'description' => 'Beautiful ocean view from the room'
            ],
            [
                'name' => 'Mountain View',
                'icon' => 'fa-mountain',
                'description' => 'Scenic mountain views from the room'
            ],
            [
                'name' => 'Garden View',
                'icon' => 'fa-leaf',
                'description' => 'Peaceful garden views from the room'
            ],

            // Bedding
            [
                'name' => 'Double Deck Beds',
                'icon' => 'fa-bed',
                'description' => 'Comfortable double deck beds for guests'
            ],
            [
                'name' => 'Extra Bedding',
                'icon' => 'fa-plus-square',
                'description' => 'Additional bedding available upon request'
            ],
            [
                'name' => 'Pillows',
                'icon' => 'fa-pillow',
                'description' => 'Comfortable pillows provided'
            ],
            [
                'name' => 'Blankets',
                'icon' => 'fa-blanket',
                'description' => 'Warm blankets provided'
            ],

            // Bathroom
            [
                'name' => 'Private Bathroom',
                'icon' => 'fa-bath',
                'description' => 'Private bathroom with shower'
            ],
            [
                'name' => 'Hot Water',
                'icon' => 'fa-hot-tub',
                'description' => 'Hot water available 24/7'
            ],
            [
                'name' => 'Toiletries',
                'icon' => 'fa-soap',
                'description' => 'Basic toiletries provided'
            ],
            [
                'name' => 'Towels',
                'icon' => 'fa-towel',
                'description' => 'Clean towels provided'
            ],

            // Technology
            [
                'name' => 'WiFi',
                'icon' => 'fa-wifi',
                'description' => 'Free WiFi access'
            ],
            [
                'name' => 'Power Outlets',
                'icon' => 'fa-plug',
                'description' => 'Multiple power outlets for charging devices'
            ],
            [
                'name' => 'TV',
                'icon' => 'fa-tv',
                'description' => 'Television with cable channels'
            ],

            // Storage
            [
                'name' => 'Closet',
                'icon' => 'fa-wardrobe',
                'description' => 'Spacious closet for your belongings'
            ],
            [
                'name' => 'Safe',
                'icon' => 'fa-vault',
                'description' => 'In-room safe for valuables'
            ],
            [
                'name' => 'Luggage Rack',
                'icon' => 'fa-suitcase',
                'description' => 'Luggage rack for your bags'
            ],

            // Additional Features
            [
                'name' => 'Desk',
                'icon' => 'fa-desk',
                'description' => 'Work desk with chair'
            ],
            [
                'name' => 'Mirror',
                'icon' => 'fa-mirror',
                'description' => 'Full-length mirror'
            ],
            [
                'name' => 'Blackout Curtains',
                'icon' => 'fa-curtain',
                'description' => 'Blackout curtains for better sleep'
            ],
            [
                'name' => 'Soundproofing',
                'icon' => 'fa-volume-mute',
                'description' => 'Soundproofed walls for peace and quiet'
            ],
            [
                'name' => 'Smoke Detector',
                'icon' => 'fa-smoke',
                'description' => 'Smoke detector for safety'
            ],
            [
                'name' => 'Fire Extinguisher',
                'icon' => 'fa-fire-extinguisher',
                'description' => 'Fire extinguisher in room'
            ],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }
    }
} 
} 