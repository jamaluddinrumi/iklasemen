<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            "title" => "rankings",
        ];
        return Inertia::render("Dashboard", $data);
    }
}
