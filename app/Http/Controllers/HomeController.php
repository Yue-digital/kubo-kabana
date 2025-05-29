<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\Home;
use App\Models\Service;
use App\Models\GallerySetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        $featuredRooms = Rooms::take(3)->get();
        $lat = 15.27995630433838;
        $lng = 120.00453604999998;

        $mapUrl = $this->generateGoogleStaticMap($lat, $lng);

        $home = Home::with('galleryImages')->first();
        return view('pages.home', compact('home','featuredRooms', 'mapUrl'));
    }

    public function about()
    {
        $featuredRooms = Rooms::take(3)->get();
        $lat = 15.27995630433838;
        $lng = 120.00453604999998;

        $mapUrl = $this->generateGoogleStaticMap($lat, $lng);
        $home = Home::with('galleryImages')->first();

        return view('pages.about', compact('featuredRooms', 'mapUrl','home'));
    }

    private function generateGoogleStaticMap($lat, $lng, $zoom = 18, $width = 1700, $height = 900)
    {
        $apiKey = config('services.google_maps.key'); // Alternatively use env('GOOGLE_MAPS_API_KEY')
        $style = [
            'element:geometry|color:0xebe3cd',
            'element:labels.text.fill|color:0x523735',
            'element:labels.text.stroke|color:0xf5f1e6',
            'feature:administrative|element:geometry.stroke|color:0xc9b2a6',
            'feature:administrative.land_parcel|element:geometry.stroke|color:0xdcd2be',
            'feature:administrative.land_parcel|element:labels.text.fill|color:0xae9e90',
            'feature:landscape.natural|element:geometry|color:0xdfd2ae',
            'feature:poi|element:geometry|color:0xdfd2ae',
            'feature:poi|element:labels.text.fill|color:0x93817c',
            'feature:poi.park|element:geometry.fill|color:0xa5b076',
            'feature:poi.park|element:labels.text.fill|color:0x447530',
            'feature:road|element:geometry|color:0xf5f1e6',
            'feature:road.arterial|visibility:off',
            'feature:road.arterial|element:geometry|color:0xfdfcf8',
            'feature:road.highway|element:geometry|color:0xf8c967',
            'feature:road.highway|element:geometry.stroke|color:0xe9bc62',
            'feature:road.highway|element:labels|visibility:off',
            'feature:road.highway.controlled_access|element:geometry|color:0xe98d58',
            'feature:road.highway.controlled_access|element:geometry.stroke|color:0xdb8555',
            'feature:road.local|visibility:off',
            'feature:road.local|element:labels.text.fill|color:0x806b63',
            'feature:transit.line|element:geometry|color:0xdfd2ae',
            'feature:transit.line|element:labels.text.fill|color:0x8f7d77',
            'feature:transit.line|element:labels.text.stroke|color:0xebe3cd',
            'feature:transit.station|element:geometry|color:0xdfd2ae',
            'feature:water|element:geometry.fill|color:0xb9d3c2',
            'feature:water|element:labels.text.fill|color:0x92998d',
        ];

        $styleParam = implode('&style=', $style);

        return "https://maps.googleapis.com/maps/api/staticmap?key={$apiKey}&center={$lat},{$lng}&zoom={$zoom}&format=png&maptype=roadmap&style={$styleParam}&size={$width}x{$height}";
    }

    public function gallery()
    {
        $gallerySettings = GallerySetting::all();
        return view('pages.gallery', compact('gallerySettings'));
    }

    public function services()
    {
        $services = Service::all();
        return view('pages.services', compact('services'));
    }
}
