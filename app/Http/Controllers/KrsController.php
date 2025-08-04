<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KrsController extends Controller
{
    public function index() {
        return view('masterdata.krs.index');
    }
}
