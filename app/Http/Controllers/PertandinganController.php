<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PertandinganController extends Controller
{
    public function index()
    {
        $data = [
            "title" => "matches",
        ];
        return Inertia::render("Pertandingan", $data);
    }
}
