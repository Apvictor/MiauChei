@extends('adminlte::page')

@section('title', 'Espécies')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a class="breadcrumb-item active" href="{{ route('species.index') }}"
                class="active">Espécies</a></li>
    </ol>
    <div style="display: flex; justify-content: space-between">
        <h1>Espécies</h1>
        <a href="{{ route('species.create') }}" class="btn btn-dark">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
    </div>

@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('species.search') }}" method="POST" class="form form-inline">
                @csrf
                <input type="text" name="filter" placeholder="Pesquisar" class="form-control"
                    value="{{ $filters['filter'] ?? '' }}">
                <button type="submit" class="btn btn-dark">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </button>
            </form>
        </div>
        <div class="card-body">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Espécies</th>
                        <th width="270">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($species as $item)
                        <tr>
                            <td><img width="30px" height="30px" src="{{ $item->image }}" alt="{{ $item->image }}"></td>
                            <td>{{ $item->name }}</td>
                            <td style="width=10px;">
                                <a href="{{ route('species.show', $item->id) }}" class="btn btn-warning" title="VER">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('species.edit', $item->id) }}" class="btn btn-info" title="EDITAR">
                                    <i class="fas fa-pen"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer" style="display: flex; justify-content: center;">
            {!! $species->links() !!}
        </div>
    </div>
@stop
