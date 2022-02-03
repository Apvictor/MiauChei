<div class="form-group">
    <label>Nome:</label>
    <input type="text" name="name" class="form-control" placeholder="Nome:" value="{{ $pet->name ?? old('name') }}">
</div>

<div class="form-group">
    <label>Foto:</label>
    <img src="{{ $pet->photo ?? '' }}" width="30" height="30">
    <input type="file" name="photo" class="form-control" placeholder="Foto:"
        value="{{ $pet->photo ?? old('photo') }}">
</div>

<div class="form-group">
    <div class="form-check">
        <input class="form-check-input" type="radio" name="sex" id="flexRadioDefault1" value="M" checked>
        <label class="form-check-label" for="flexRadioDefault1">
            Macho
        </label>
    </div>
    <div class="form-check">
        <input class="form-check-input" type="radio" name="sex" id="flexRadioDefault2" value="F">
        <label class="form-check-label" for="flexRadioDefault2">
            Fêmea
        </label>
    </div>
</div>

<div class="form-group">
    <label for="species" class="form-label">Espécie:</label>
    <input class="form-control" list="list-species" name="species" id="species"
        placeholder="Escreva para pesquisar..." value="{{ $pet->species ?? old('species') }}">
    <datalist id="list-species">
        <option value="Cachorro">
        <option value="Gato">
        <option value="Hamster">
        <option value="Coelho">
        <option value="Tartarugas">
    </datalist>
</div>

<div class="form-group">
    <label for="breed" class="form-label">Raça:</label>
    <input class="form-control" list="list-breed" name="breed" id="breed" value="{{ $pet->breed ?? old('breed') }}"
        placeholder="Escreva para pesquisar...">
    <datalist id="list-breed">
        <option value="Akita">
        <option value="Basset hound">
        <option value="Beagle">
        <option value="Bichon frisé">
        <option value="Boiadeiro australiano">
        <option value="Border collie">
        <option value="Boston terrier">
        <option value="Boxer">
        <option value="Buldogue francês">
        <option value="Buldogue inglês">
        <option value="Bull terrier">
        <option value="Cane corso">
        <option value="Cavalier king charles spaniel">
        <option value="Chihuahua">
        <option value="Chow chow">
        <option value="Cocker spaniel inglês">
        <option value="Dachshund">
        <option value="Dálmata">
        <option value="Doberman">
        <option value="Dogo argentino">
        <option value="Dogue alemão">
        <option value="Fila brasileiro">
        <option value="Golden retriever">
        <option value="Husky siberiano">
        <option value="Jack russell terrier">
        <option value="Labrador retriever">
        <option value="Lhasa apso">
        <option value="Lulu da pomerânia">
        <option value="Maltês">
        <option value="Mastiff inglês">
        <option value="Mastim tibetano">
        <option value="Pastor alemão">
        <option value="Pastor australiano">
        <option value="Pastor de Shetland">
        <option value="Pequinês">
        <option value="Pinscher">
        <option value="Pit bull">
        <option value="Poodle">
        <option value="Pug">
        <option value="Rottweiler">
        <option value="Schnauzer">
        <option value="Shar-pei">
        <option value="Shiba">
        <option value="Shih tzu">
        <option value="Staffordshire bull terrier">
        <option value="Weimaraner">
        <option value="Yorkshire">
        <option value="Vira Lata">
    </datalist>
</div>

<div class="form-group">
    <label for="size" class="form-label">Tamanho:</label>
    <input class="form-control" list="list-size" name="size" id="size" value="{{ $pet->size ?? old('size') }}"
        placeholder="Escreva para pesquisar...">
    <datalist id="list-size">
        <option value="Pequeno">
        <option value="Médio">
        <option value="Grande">
    </datalist>
</div>

<div class="form-group">
    <label for="predominant" class="form-label">Cor Predominante:</label>
    <input class="form-control" list="list-predominant" name="predominant_color" id="predominant"
        value="{{ $pet->predominant_color ?? old('predominant_color') }}" placeholder="Escreva para pesquisar...">
    <datalist id="list-predominant">
        <option value="Preto">
        <option value="Branco">
        <option value="Marrom">
    </datalist>
</div>

<div class="form-group">
    <label for="secondary" class="form-label">Cor Secundária:</label>
    <input class="form-control" list="list-secondary" name="secondary_color" id="secondary"
        value="{{ $pet->secondary_color ?? old('secondary_color') }}" placeholder="Escreva para pesquisar...">
    <datalist id="list-secondary">
        <option value="Preto">
        <option value="Branco">
        <option value="Marron">
    </datalist>
</div>

<div class="form-group">
    <label for="secondary" class="form-label">Detalhes Físico:</label>
    <textarea class="form-control" name="physical_details" placeholder="Detalhes Físico"
        value="{{ $pet->physical_details ?? old('physical_details') }}" style="height: 100px"></textarea>
</div>

<div class="form-group">
    <label>Data de Desaparecimento:</label>
    <input type="date" name="date_disappearance" class="form-control"
        value="{{ $pet->date_disappearance ?? old('date_disappearance') }}">
</div>

<div class="form-group">
    <label>Última vez visto:</label>
    <input type="text" name="last_seen" class="form-control" placeholder="Rua:">
</div>

<div class="form-group">
    <button type="submit" class="btn btn-dark">Enviar</button>
</div>
