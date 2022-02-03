<?php

namespace App\Http\Controllers;

use App\Models\Pets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetsController extends Controller
{
    public function show($id)
    {
        $pet = Pets::findOrFail($id);

        return view('admin.pages.pets.show', compact('pet'));
    }

    public function edit($id)
    {
        $pet = Pets::findOrFail($id);

        return view('admin.pages.pets.edit', compact('pet'));
    }

    public function update(Request $request, $id)
    {
        $dados = $request->all();

        $pet = Pets::findOrFail($id);

        if ($request->hasFile('photo') && $request->photo->isValid()) {

            $ext = $request->photo->extension();

            if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                // SUBIR PARA SERVER AWS
                $folder = 'pets/';
                $name = $folder . $pet->uuid . '.' . $ext;
                $file = $request->file('photo');
                Storage::disk('s3')->put($name, file_get_contents($file));
                $url = Storage::disk('s3')->url($name);
                $dados['photo'] = $url;
            } else {
                return response()->json(['message' => 'Formato inválido!'], 400);
            }
        }

        $pet->update($dados);

        return redirect()->route('pets.lost.index');
    }

    public function destroy($id)
    {
        $pet = Pets::findOrFail($id);

        $file_cortado = explode('.com', $pet->photo);

        if (Storage::disk('s3')->exists(isset($file_cortado[1]) ? $file_cortado[1] : null)) {
            Storage::disk('s3')->delete($file_cortado[1]);
        }

        $pet->delete();

        return redirect()->route('pets.lost.index');
    }
}
