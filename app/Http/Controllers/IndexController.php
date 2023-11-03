<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $this
            ->seo()
            ->setTitle('صفحه اصلی لاراول')
            ->setDescription('به وبسایت لاراول خوش آمدید');

        return view('index');
    }
}
