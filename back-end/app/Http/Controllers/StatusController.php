<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        $status = Status::paginate();

        return view('admin.pages.status.index', ['status' => $status]);
    }

    public function create()
    {
        return view('admin.pages.status.create');
    }

    public function show($id)
    {
        $status = Status::findOrFail($id);

        return view('admin.pages.status.show', compact('status'));
    }

    public function edit($id)
    {
        $status = Status::findOrFail($id);

        return view('admin.pages.status.edit', compact('status'));
    }

    public function store(Request $request)
    {
        $dados = $request->all();

        Status::create($dados);

        return redirect()->route('status.index');
    }

    public function update(Request $request, $id)
    {
        $dados = $request->all();

        $status = Status::findOrFail($id);

        $status->update($dados);

        return redirect()->route('status.index');
    }

    public function destroy($id)
    {
        $status = Status::findOrFail($id);

        $status->delete();

        return redirect()->route('status.index');
    }

    public function search(Request $request)
    {
        $filters = $request->only('filter');

        $status = Status::where('name', 'LIKE', "%{$request->filter}%")
            ->latest()
            ->paginate();

        return view('admin.pages.status.index', compact('status'));
    }
}
