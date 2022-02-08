<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pets;
use App\Models\Sighted;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\SerializableClosure\Serializers\Signed;

class PetsLostController extends Controller
{
    public function lostIndex()
    {
        $pets = Pets::where('status_id', 1)->orderBy('date_disappearance', 'DESC')->paginate(3);

        return view('admin.pages.pets.pets_lost.index', compact('pets'));
    }

    public function lostCreate()
    {
        return view('admin.pages.pets.pets_lost.create');
    }

    public function lostStore(Request $request)
    {
        $validator  = Validator::make($request->all(), [
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
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
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
        $pet->physical_details = $dados['physical_details'];
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

    public function lostSearch(Request $request)
    {
        $filters = $request->only('filter');

        $pets = Pets::where('status_id', 1)
            ->where('name', 'LIKE', "%{$request->filter}%")
            ->orWhere('breed', $request->filter)
            ->latest()
            ->paginate();

        return view('admin.pages.pets.pets_lost.index', compact('pets'));
    }

    public function foundPet($id)
    {
        $pet = Pets::FindOrFail($id);

        $pet->update(['status_id' => 2]);

        return redirect()->route('pets.lost.index')->with('toast_success', 'Atualizado com sucesso!');
    }
}
