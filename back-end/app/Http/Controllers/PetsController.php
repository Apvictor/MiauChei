<?php

namespace App\Http\Controllers;

use App\Models\Pets;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PetsController extends Controller
{
    /**
     * Retorna detalhes do pet
     *
     * @param integer $id
     * @return View|Factory
     */
    public function show(int $id)
    {
        $pet = Pets::findOrFail($id);

        return view('admin.pages.pets.show', compact('pet'));
    }

    /**
     * Retorna formulário de edição
     *
     * @param integer $id
     * @return View|Factory
     */
    public function edit(int $id)
    {
        $pet = Pets::join('sighted', 'sighted.pet_id', '=', 'pets.id')->select('pets.*', 'sighted.last_seen')->findOrFail($id);

        return view('admin.pages.pets.edit', compact('pet'));
    }

    /**
     * Editar pet
     *
     * @param Request $request
     * @param integer $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $dados = $request->all();

        $pet = Pets::findOrFail($id);
        $dados['uuid'] = Str::uuid();

        if ($request->hasFile('photo') && $request->photo->isValid()) {

            $ext = $request->photo->extension();

            if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                // SUBIR PARA SERVER AWS
                $folder = 'pets/';
                $name = $folder . $dados['uuid'] . '.' . $ext;
                $file = $request->file('photo');
                Storage::disk('s3')->put($name, file_get_contents($file));
                $url = Storage::disk('s3')->url($name);
                $dados['photo'] = $url;
            } else {
                return back()->with('toast_error', 'Formato de foto inválido')->withInput();
            }
        }

        $pet->update($dados);

        return redirect()->route('pets.lost.index')->with('toast_success', 'Atualizado com sucesso!');
    }

    /**
     * Deletar pet
     *
     * @param integer $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $pet = Pets::findOrFail($id);

        $file_cortado = explode('.com', $pet->photo);

        if (Storage::disk('s3')->exists($file_cortado[1] ?? null)) {
            Storage::disk('s3')->delete($file_cortado[1]);
        } else {
            return back()->with('toast_error', 'Erro ao excluir foto')->withInput();
        }

        $pet->delete();

        return redirect()->route('pets.lost.index')->with('toast_success', 'Deletado com sucesso!');
    }
}
