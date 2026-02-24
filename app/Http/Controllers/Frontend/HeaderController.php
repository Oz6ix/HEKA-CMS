<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Redirect;

class HeaderController extends Controller
{
    public function __construct()
    {
//        $this->directory_logos = "logos";
        $this->page_info = [];
    }

    /* Page events */
    public function filter()
    {
        return view('frontend.home.home')->with($this->page_info);
    }
}
