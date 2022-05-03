@extends('adminlte::page')

@section('title', "Editar a raça {$breeds->name}")

@section('content_header')
    <h1>Editar a raça {{ $breeds->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('breeds.update', $breeds->id) }}" class="form" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('admin.pages.breeds._partials.form')
            </form>
        </div>
    </div>
@endsection
