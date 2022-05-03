@extends('adminlte::page')

@section('title', 'Cadastrar Nova Raça')

@section('content_header')
    <h1>Cadastrar Nova Raça</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('breeds.store') }}" class="form" method="POST" enctype="multipart/form-data">
                @csrf

                @include('admin.pages.breeds._partials.form')
            </form>
        </div>
    </div>
@endsection
