<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pets;

class PetsFoundController extends Controller
{
    public function foundIndex()
    {
        $pets = Pets::where('status_id', 2)->orderBy('date_disappearance', 'DESC')->paginate(3);

        return view('admin.pages.pets.pets_found.index', compact('pets'));
    }

    public function foundSearch(Request $request)
    {
        $filters = $request->only('filter');

        $pets = Pets::where('status_id', 2)
            ->where('name', 'LIKE', "%{$request->filter}%")
            ->orWhere('breed', $request->filter)
            ->latest()
            ->paginate();

        return view('admin.pages.pets.pets_found.index', compact('pets'));
    }

    public function lostPet($id)
    {
        $pet = Pets::FindOrFail($id);

        $pet->update(['status_id' => 1]);

        return redirect()->route('pets.found.index')->with('toast_success', 'Atualizado com sucesso!');
    }
}
