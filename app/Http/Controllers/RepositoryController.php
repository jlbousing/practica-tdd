<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repository;
use App\Http\Requests\RepositoryRequest;
use Illuminate\Support\Facades\Auth;

class RepositoryController extends Controller
{
    public function index(Request $request)
    {

        return view("repositories.index",[
            "repositories" => $request->user()->repositories
        ]);
    }

    public function show(Repository $repository)
    {
        $this->authorize("pass",$repository);

        return view("repositories.show",[
            "repository" => $repository
        ]);
    }

    public function create()
    {
        return view("repositories.create");
    }


    public function store(RepositoryRequest $request)
    {
        $request->user()->repositories()->create($request->all());
        return redirect()->route("repositories.index");
    }

    public function edit(Repository $repository)
    {
        $this->authorize("pass",$repository);

        return view("repositories.edit",[
            "repository" => $repository
        ]);
    }

    public function update(RepositoryRequest $request, Repository $repository)
    {

        $this->authorize("pass",$repository);

        $repository->update($request->all());
        return redirect()->route("repositories.edit",$repository);
    }

    public function destroy(Repository $repository)
    {
        $this->authorize("pass",$repository);

        $repository->delete();

        return redirect("repositories");
    }
}
