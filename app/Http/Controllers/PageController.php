<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home()
    {
        $repository = Repository::latest()->get();


        return view("welcome",[
            "repositories" => $repository
        ]);
    }
}
