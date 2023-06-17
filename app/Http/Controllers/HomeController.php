<?php

namespace App\Http\Controllers;


use App\Models\Blog;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $getList   = Blog::get();

        $view_data = [
            'data' => $getList
        ];

        return view('home', $view_data);
    }
}
