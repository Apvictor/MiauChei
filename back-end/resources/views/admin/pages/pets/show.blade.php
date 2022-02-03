@extends('adminlte::page')

@section('title', "Detalhes do Pet {$pet->name}")

@section('content_header')
    <h1>Detalhes do Pet <b>{{ $pet->name }}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <ul>
                <li>
                    <strong>Pet: </strong> {{ $pet->name }}
                </li>
            </ul>

            <form action="{{ route('pets.destroy', $pet->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> DELETAR O PET
                    <b>{{ $pet->name }}</b>
                </button>
            </form>
        </div>
    </div>
@endsection
