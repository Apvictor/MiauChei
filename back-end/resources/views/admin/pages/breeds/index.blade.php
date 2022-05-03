@extends('adminlte::page')

@section('title', 'Raças')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a class="breadcrumb-item active" href="{{ route('breeds.index') }}"
                class="active">Raças</a></li>
    </ol>
    <div style="display: flex; justify-content: space-between">
        <h1>Raças</h1>
        <a href="{{ route('breeds.create') }}" class="btn btn-dark">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
    </div>

@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between;">
                <form action="{{ route('breeds.search') }}" method="POST" class="form form-inline">
                    @csrf
                    <input type="text" name="filter" placeholder="Pesquisar" class="form-control"
                        value="{{ $filters['filter'] ?? '' }}">

                    <button type="submit" class="btn btn-dark">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                </form>

                <div style="display: flex;">
                    <form style="margin: 0 10px;" action="{{ route('breeds.imports', 'dog') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-dark" title="IMPORTAÇÃO DE RAÇAS DE CACHORROS">
                            <i class="fa fa-file-import"></i>
                        </button>
                    </form>

                    <form action="{{ route('breeds.imports', 'cat') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-dark" title="IMPORTAÇÃO DE RAÇAS DE GATOS">
                            <i class="fa fa-file-import"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Raça</th>
                        <th width="270">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($breeds as $item)
                        <tr>
                            <td><img width="30px" height="30px" src="{{ $item->image }}" alt="{{ $item->image }}"></td>
                            <td>{{ $item->name }}</td>
                            <td style="width=10px;">
                                <a href="{{ route('breeds.show', $item->id) }}" class="btn btn-warning" title="VER">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('breeds.edit', $item->id) }}" class="btn btn-info" title="EDITAR">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer" style="display: flex; justify-content: center;">
            {!! $breeds->links() !!}
        </div>
    </div>
@stop
