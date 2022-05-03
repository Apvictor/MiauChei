@extends('adminlte::page')

@section('title', "Editar a espécies {$species->name}")

@section('content_header')
    <h1>Editar a espécies {{ $species->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('species.update', $species->id) }}" class="form" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('admin.pages.species._partials.form')
            </form>
        </div>
    </div>
@endsection
