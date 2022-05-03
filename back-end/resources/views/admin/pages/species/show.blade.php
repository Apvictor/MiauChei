@extends('adminlte::page')

@section('title', "Detalhes da espécie {$species->name}")

@section('content_header')
    <h1>Detalhes da espécie <b>{{ $species->name }}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <ul>
                <li>
                    <strong>Espécie: </strong> {{ $species->name }}
                </li>
            </ul>

            <form action="{{ route('species.destroy', $species->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash" title="DELETAR"></i> DELETAR A ESPÉCIE
                    <b>{{ $species->name }}</b>
                </button>
            </form>
        </div>
    </div>
@endsection
