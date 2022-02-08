<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(3);

        return view('admin.pages.users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('admin.pages.users.create');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('admin.pages.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.pages.users.edit', compact('user'));
    }

    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'name' => ['required'],
            'phone' => ['required', 'unique:users'],
            'photo' => ['required', 'mimes:jpg,png,jpeg'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'max:8'],
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        }

        $dados = $request->all();

        $dados['password'] = bcrypt($request->password);
        $dados['uuid'] = Str::uuid();

        if ($request->hasFile('photo') && $request->photo->isValid()) {

            $ext = $request->photo->extension();

            if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                // SUBIR PARA SERVER AWS
                $folder = 'users/';
                $name = $folder . $dados['uuid'] . '.' . $ext;
                $file = $request->file('photo');
                Storage::disk('s3')->put($name, file_get_contents($file));
                $url = Storage::disk('s3')->url($name);
                $dados['photo'] = $url;
            } else {
                return back()->with('toast_error', 'Formato de foto inválido')->withInput();
            }
        }

        User::create($dados);

        return redirect()->route('users.index')->with('toast_success', 'Cadastrado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $validator  = Validator::make($request->all(), [
            'name' => ['required'],
            'phone' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'max:8'],
        ]);

        if ($validator->fails()) {
            return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
        }

        $dados = $request->all();

        $user = User::findOrFail($id);

        $dados['password'] = bcrypt($request->password);

        if ($request->hasFile('photo') && $request->photo->isValid()) {

            $ext = $request->photo->extension();

            if ($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg') {
                // SUBIR PARA SERVER AWS
                $folder = 'users/';
                $name = $folder . $user->uuid . '.' . $ext;
                $file = $request->file('photo');
                Storage::disk('s3')->put($name, file_get_contents($file));
                $url = Storage::disk('s3')->url($name);
                $dados['photo'] = $url;
            } else {
                return back()->with('toast_error', 'Formato de foto inválido')->withInput();
            }
        }

        $user->update($dados);

        return redirect()->route('users.index')->with('toast_success', 'Atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $file_cortado = explode('.com', $user->photo);

        if (Storage::disk('s3')->exists(isset($file_cortado[1]) ? $file_cortado[1] : null)) {
            Storage::disk('s3')->delete($file_cortado[1]);
        } else {
            return back()->with('toast_error', 'Erro ao excluir foto')->withInput();
        }

        $user->delete();

        return redirect()->route('users.index')->with('toast_success', 'Deletado com sucesso!');
    }

    public function search(Request $request)
    {
        $filters = $request->only('filter');

        $users = User::where('name', 'LIKE', "%{$request->filter}%")
            ->orWhere('email', $request->filter)
            ->latest()
            ->paginate();

        return view('admin.pages.users.index', compact('users'));
    }
}
