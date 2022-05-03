<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Breeds;
use App\Models\Species;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BreedsController extends Controller
{
    /**
     * Lista de Raças
     *
     * @return View|Factory
     */
    public function index()
    {
        $breeds = Breeds::orderBy('name', 'ASC')->paginate(10);

        return view('admin.pages.breeds.index', compact('breeds'));
    }

    /**
     * Retorna formulário de criação
     *
     * @return View|Factory
     */
    public function create()
    {
        $species = Species::all();

        return view('admin.pages.breeds.create', compact('species'));
    }

    /**
     * Retorna detalhes da raças
     *
     * @param integer $id
     * @return View|Factory
     */
    public function show(int $id)
    {
        $breeds = Breeds::findOrFail($id);

        return view('admin.pages.breeds.show', compact('breeds'));
    }

    /**
     * Retorna formulário de edição
     *
     * @param integer $id
     * @return View|Factory
     */
    public function edit(int $id)
    {
        $species = Species::all();

        $breeds = Breeds::join('species', 'breeds.species_id', '=', 'species.id')
            ->select(
                'breeds.*',
                'species.name as species'
            )
            ->findOrFail($id);

        return view('admin.pages.breeds.edit', compact('breeds', 'species'));
    }

    /**
     * Criação de raças
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'image' => ['mimes:jpg,png,jpeg'],
            'species_id' => ['required']
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        $dados = $request->all();

        if ($request->hasFile('image') && $request->image->isValid()) {

            $ext = $request->image->extension();

            if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                // SUBIR PARA SERVER AWS
                $folder = 'breeds/';
                $name = $folder . Str::uuid() . '.' . $ext;
                $file = $request->file('image');
                Storage::disk('s3')->put($name, file_get_contents($file));
                $url = Storage::disk('s3')->url($name);
                $dados['image'] = $url;
            } else {
                return back()->with('toast_error', 'Formato de foto inválido')->withInput();
            }
        }

        Breeds::create($dados);

        return redirect()->route('breeds.index')->with('toast_success', 'Cadastrado com sucesso!');
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
            'species_id' => ['required']
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        $breeds = Breeds::findOrFail($id);

        $dados = $request->all();

        if ($request->hasFile('image') && $request->image->isValid()) {

            $ext = $request->image->extension();

            if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                // SUBIR PARA SERVER AWS
                $folder = 'breeds/';
                $name = $folder . Str::uuid() . '.' . $ext;
                $file = $request->file('image');
                Storage::disk('s3')->put($name, file_get_contents($file));
                $url = Storage::disk('s3')->url($name);
                $dados['image'] = $url;
            } else {
                return back()->with('toast_error', 'Formato de foto inválido')->withInput();
            }
        }

        $breeds->update($dados);

        return redirect()->route('breeds.index')->with('toast_success', 'Atualizado com sucesso!');
    }

    /**
     * Deletar Espécie
     *
     * @param integer $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $breeds = Breeds::findOrFail($id);

        $breeds->delete();

        return redirect()->route('breeds.index')->with('toast_success', 'Deletado com sucesso!');
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

        $breeds = Breeds::where('name', 'LIKE', "%$request->filter%")
            ->latest()
            ->paginate();

        return view('admin.pages.breeds.index', compact('breeds'));
    }


    /**
     * Importar Raças
     *
     * @return void
     */
    public function imports($type)
    {
        $storage = Storage::disk('public');
        $directories = $storage->directories();

        foreach ($directories as $key => $value) {
            if ($value == 'breeds') {
                $files = $storage->files($value);
            }
        }

        switch ($type) {
            case 'dog':
                $tipo = 1;
                $get = $storage->get($files[1]);
                break;
            case 'cat':
                $tipo = 2;
                $get = $storage->get($files[0]);
                break;
            default:
        }

        $result_storage = json_decode($get)->list;

        $list = [];
        foreach ($result_storage as $key => $value) {
            array_push($list, $value);
        }

        $breeds = Breeds::select('*')->where('species_id', $tipo)->get();

        for ($i = 0; $i < count($list); $i++) {
            $name_pet = $list[$i]->name;
            $image_pet = $list[$i]->url;


            for ($y = 0; $y < count($breeds); $y++) {
                if ($breeds[$y]->name == $name_pet) {
                    break;
                }
            }

            if (count($breeds) == $y) {

                if ($image_pet != null) {
                    $ext = pathinfo($image_pet, PATHINFO_EXTENSION);
                    $folder = 'breeds/';
                    $name = $folder . Str::uuid() . '.' . $ext;
                    Storage::disk('s3')->put($name, file_get_contents($image_pet));
                    $url = Storage::disk('s3')->url($name);
                }

                Breeds::create([
                    'name' => $name_pet,
                    'image' => $url ?? null,
                    'species_id' => $tipo
                ]);
            }
        }

        return redirect()->route('breeds.index')->with('toast_success', 'Importado com sucesso!');
    }
}
