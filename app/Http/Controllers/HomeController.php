<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Pennant\Feature;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        return view('home');
    }

    public function comment(Request $request)
    {
//        if (! $request->ajax()) {
//            return  response()->json([
//                'status' => 'just ajax request'
//            ]);
//        }
        $validData = $request->validate([
           'commentable_id' => 'required',
           'commentable_type' => 'required',
            'parent_id' => 'required',
            'comment' => 'required'
        ]);

        auth()->user()->comments()->create($validData);

//        return response()->json([
//           'status' => 'success'
//        ]);

        Alert::success('نظر با موفقیت ثبت شد');
        return back();
    }
}
