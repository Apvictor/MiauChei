<div class="form-group">
    <label>Espécie:</label>
    <input type="text" name="name" class="form-control" placeholder="Espécie:"
        value="{{ $species->name ?? old('name') }}">
</div>

<div class="form-group">
    <label>Imagem:</label>
    <img src="{{ $species->image ?? '' }}" width="30" height="30">
    <input type="file" name="image" class="form-control" placeholder="Imagem:"
        value="{{ $species->image ?? old('image') }}">
</div>

<div class="form-group">
    <button type="submit" class="btn btn-dark">Enviar</button>
</div>
