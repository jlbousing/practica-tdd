<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Repository;
use App\Http\Requests\RepositoryRequest;

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
        $repository->update($request->all());
        return redirect()->route("repositories.edit",$repository);
    }

    public function destroy(Repository $repository)
    {
        $repository->delete();

        return redirect("repositories");
    }
}
