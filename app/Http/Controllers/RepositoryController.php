<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repository;
use App\Http\Requests\RepositoryRequest;
use Illuminate\Support\Facades\Auth;

class RepositoryController extends Controller
{
    public function index()
    {

    }

    public function store(RepositoryRequest $request)
    {
        $request->user()->repositories()->create($request->all());
        return redirect()->route("repositories.index");
    }

    public function update(RepositoryRequest $request, Repository $repository)
    {

        if($request->user()->id != $repository->user_id){
            abort(403);
        }

        $repository->update($request->all());
        return redirect()->route("repositories.edit",$repository);
    }

    public function destroy(Repository $repository)
    {

        if(Auth::user()->id != $repository->user_id){
            abort(403);
        }

        $repository->delete();

        return redirect("repositories");
    }
}
