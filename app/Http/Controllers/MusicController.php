<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;

class MusicController extends Controller
{
    /**
     * By Claude
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function adminView()
    {
        $tracks = Song::all();
        return view('adminView', compact('tracks'));
    }

    public function clientView()
    {
        return view('clientView');
    }
}
