<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return view('pages.admin.room');
    }

    public function roomDetail()
    {
        return view('pages.admin.room-detail');
    }
}
