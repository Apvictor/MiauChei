<div class="form-group">
    <label>Espécie:</label>
    <select class="form-control" name="species_id">
        <option value="{{ $item->id ?? '' }}" selected>{{ $breeds->species ?? 'Selecione uma Espécie' }}</option>
        @forelse ($species as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
        @empty
            <option>Sem registros</option>
        @endforelse
    </select>
</div>

<div class="form-group">
    <label>Raça:</label>
    <input type="text" name="name" class="form-control" placeholder="Raça:"
        value="{{ $breeds->name ?? old('name') }}">
</div>

<div class="form-group">
    <label>Imagem:</label>
    <img src="{{ $breeds->image ?? '' }}" width="30" height="30">
    <input type="file" name="image" class="form-control" placeholder="Imagem:"
        value="{{ $breeds->image ?? old('image') }}">
</div>


<div class="form-group">
    <button type="submit" class="btn btn-dark">Enviar</button>
</div>
