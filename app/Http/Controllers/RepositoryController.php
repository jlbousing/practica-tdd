<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repository;
use Illuminate\Support\Facades\Auth;

class RepositoryController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
    {
        $request->user()->repositories()->create($request->all());
        return redirect()->route("repositories.index");
    }

    public function update(Request $request, Repository $repository)
    {
        //$data = $request->all();
        //$repository->url = $data["url"];
        //$repository->description = $data["description"];
        //$repository->save();

        $repository->update($request->all());
        return redirect()->route("repositories.edit",$repository);
    }
}
