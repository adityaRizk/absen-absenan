<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function index()
    {
        return Inertia::render('Locations/Index');
    }

    public function foto()
    {
        return Inertia::render('Locations/Create');
    }
}
