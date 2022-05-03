@extends('adminlte::page')

@section('title', "Detalhes da raça {$breeds->name}")

@section('content_header')
    <h1>Detalhes da raça <b>{{ $breeds->name }}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <ul>
                <li>
                    <strong>Raça: </strong> {{ $breeds->name }}
                </li>
            </ul>

            <form action="{{ route('breeds.destroy', $breeds->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash" title="DELETAR"></i> DELETAR A RAÇA
                    <b>{{ $breeds->name }}</b>
                </button>
            </form>
        </div>
    </div>
@endsection
