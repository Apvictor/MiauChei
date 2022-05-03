@extends('adminlte::page')

@section('title', 'Cadastrar Nova Espécie')

@section('content_header')
    <h1>Cadastrar Nova Espécie</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('species.store') }}" class="form" method="POST" enctype="multipart/form-data">
                @csrf

                @include('admin.pages.species._partials.form')
            </form>
        </div>
    </div>
@endsection
