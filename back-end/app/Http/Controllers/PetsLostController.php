<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Pets;
use App\Models\Sighted;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PetsLostController extends Controller
{
    /**
     * Lista de pets perdidos
     *
     * @return View|Factory
     */
    public function lostIndex()
    {
        $pets = Pets::where('status_id', 1)->orderBy('updated_at', 'DESC')->paginate(5);

        return view('admin.pages.pets.pets_lost.index', compact('pets'));
    }

    /**
     * Formulário de criação de pet
     *
     * @return View|Factory
     */
    public function lostCreate()
    {
        return view('admin.pages.pets.pets_lost.create');
    }

    /**
     * Criação de pet
     *
     * @param Request $request
     * @return Redirector|RedirectResponse
     */
    public function lostStore(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required'],
            'sex' => ['required'],
            'photo' => ['required', 'mimes:jpg,png,jpeg'],
            'species' => ['required'],
            'breed' => ['required'],
            'size' => ['required'],
            'predominant_color' => ['required'],
            'secondary_color' => ['required'],
            'date_disappearance' => ['required'],
            'last_seen' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all())->withInput();
        }

        $dados = $request->all();

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

        $pet = new Pets();
        $pet->name = $dados['name'];
        $pet->species = $dados['species'];
        $pet->sex = $dados['sex'];
        $pet->breed = $dados['breed'];
        $pet->size = $dados['size'];
        $pet->predominant_color = $dados['predominant_color'];
        $pet->secondary_color = $dados['secondary_color'];
        $pet->physical_details = $dados['physical_details'] ?? null;
        $pet->date_disappearance = $dados['date_disappearance'];
        $pet->photo = $dados['photo'];
        $pet->uuid = $dados['uuid'];
        $pet->user_id = Auth::user()->id;
        $pet->status_id = 1;

        $pet->save();

        $sighted = new Sighted();
        $sighted->last_seen = $dados['last_seen'];
        $sighted->data_sighted = $dados['date_disappearance'];
        $sighted->user_id = Auth::user()->id;

        $pet->sighted()->save($sighted);

        return redirect()->route('pets.lost.index')->with('toast_success', 'Cadastrado com sucesso!');
    }

    /**
     * Buscar pet
     *
     * @param Request $request
     * @return View|Factory
     */
    public function lostSearch(Request $request)
    {
        $request->only('filter');

        $pets = Pets::where('status_id', 1)
            ->where('name', 'LIKE', "%$request->filter%")
            ->orWhere('breed', $request->filter)
            ->latest()
            ->paginate();

        return view('admin.pages.pets.pets_lost.index', compact('pets'));
    }

    /**
     * Atualizar pet para encontrado
     *
     * @param integer $id
     * @return RedirectResponse
     */
    public function foundPet(int $id): RedirectResponse
    {
        $pet = Pets::FindOrFail($id);

        $pet->update(['status_id' => 2]);

        return redirect()->route('pets.lost.index')->with('toast_success', 'Atualizado com sucesso!');
    }
}
