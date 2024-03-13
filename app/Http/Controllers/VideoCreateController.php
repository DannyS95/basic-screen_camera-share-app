<?php

namespace App\Http\Controllers;

class VideoCreateController extends Controller
{
    public function __invoke()
    {
        return inertia()->render('Videos/Create');
    }
}
