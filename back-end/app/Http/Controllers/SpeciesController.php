<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Species;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SpeciesController extends Controller
{
    /**
     * Lista de Espécies
     *
     * @return View|Factory
     */
    public function index()
    {
        $species = Species::paginate(10);

        return view('admin.pages.species.index', ['species' => $species]);
    }

    /**
     * Retorna formulário de criação
     *
     * @return View|Factory
     */
    public function create()
    {
        return view('admin.pages.species.create');
    }

    /**
     * Retorna detalhes da espécie
     *
     * @param integer $id
     * @return View|Factory
     */
    public function show(int $id)
    {
        $species = Species::findOrFail($id);

        return view('admin.pages.species.show', compact('species'));
    }

    /**
     * Retorna formulário de edição
     *
     * @param integer $id
     * @return View|Factory
     */
    public function edit(int $id)
    {
        $species = Species::findOrFail($id);

        return view('admin.pages.species.edit', compact('species'));
    }

    /**
     * Criação de espécie
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'image' => ['mimes:jpg,png,jpeg'],
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        $dados = $request->all();

        if ($request->hasFile('image') && $request->image->isValid()) {

            $ext = $request->image->extension();

            if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                // SUBIR PARA SERVER AWS
                $folder = 'species/';
                $name = $folder . Str::uuid() . '.' . $ext;
                $file = $request->file('image');
                Storage::disk('s3')->put($name, file_get_contents($file));
                $url = Storage::disk('s3')->url($name);
                $dados['image'] = $url;
            } else {
                return back()->with('toast_error', 'Formato de foto inválido')->withInput();
            }
        }

        Species::create($dados);

        return redirect()->route('species.index')->with('toast_success', 'Cadastrado com sucesso!');
    }

    /**
     * Edição de espécie
     *
     * @param Request $request
     * @param integer $id
     * @return RedirectResponse
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'image' => ['mimes:jpg,png,jpeg'],
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        $species = Species::findOrFail($id);

        $dados = $request->all();

        if ($request->hasFile('image') && $request->image->isValid()) {

            $ext = $request->image->extension();

            if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                // SUBIR PARA SERVER AWS
                $folder = 'species/';
                $name = $folder . Str::uuid() . '.' . $ext;
                $file = $request->file('image');
                Storage::disk('s3')->put($name, file_get_contents($file));
                $url = Storage::disk('s3')->url($name);
                $dados['image'] = $url;
            } else {
                return back()->with('toast_error', 'Formato de foto inválido')->withInput();
            }
        }

        $species->update($dados);

        return redirect()->route('species.index')->with('toast_success', 'Atualizado com sucesso!');
    }

    /**
     * Deletar Espécie
     *
     * @param integer $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $species = Species::findOrFail($id);

        $species->delete();

        return redirect()->route('species.index')->with('toast_success', 'Deletado com sucesso!');
    }

    /**
     * Buscar espécie
     *
     * @param Request $request
     * @return View|Factory
     */
    public function search(Request $request)
    {
        $request->only('filter');

        $species = Species::where('name', 'LIKE', "%$request->filter%")
            ->latest()
            ->paginate();

        return view('admin.pages.species.index', compact('species'));
    }
}
