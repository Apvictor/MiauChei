<div class="form-group">
    <label>Nome:</label>
    <input type="text" name="name" class="form-control" placeholder="Nome:" value="{{ $user->name ?? old('name') }}">
</div>

<div class="form-group">
    <label>Telefone:</label>
    <input type="text" name="phone" class="form-control" placeholder="Telefone:"
        value="{{ $user->phone ?? old('phone') }}">
</div>

<div class="form-group">
    <label>Foto:</label>
    <img src="{{ $user->photo ?? '' }}" width="30" height="30">
    <input type="file" name="photo" class="form-control" placeholder="Foto:"
        value="{{ $user->photo ?? old('photo') }}">
</div>

<div class="form-group">
    <label>E-mail:</label>
    <input type="email" name="email" class="form-control" placeholder="Nome:"
        value="{{ $user->email ?? old('email') }}">
</div>

<div class="form-group">
    <label>Senha:</label>
    <input type="password" name="password" class="form-control" placeholder="Senha:">
</div>






<div class="form-group">
    <button type="submit" class="btn btn-dark">Enviar</button>
</div>
