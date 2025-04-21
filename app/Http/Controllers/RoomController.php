<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Rooms::all();
        return view('pages.rooms.index', compact('rooms'));
    }

    public function show(Rooms $room)
    {
        return view('pages.rooms.show', compact('room'));
    }
}
