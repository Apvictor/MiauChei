@extends('adminlte::page')

@section('title', 'Pets Perdidos')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a class="breadcrumb-item active" href="{{ route('pets.lost.index') }}"
                class="active">Pets Perdidos</a></li>
    </ol>
    <div style="display: flex; justify-content: space-between">
        <h1>Pets Perdidos</h1>
        <a href="{{ route('pets.lost.create') }}" class="btn btn-dark">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
    </div>

@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <form action="{{ route('pets.lost.search') }}" method="POST" class="form form-inline">
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
                        <th>Nome</th>
                        <th>Espécie</th>
                        <th>Sexo</th>
                        <th>Raça</th>
                        <th>Tamanho</th>
                        <th>1° Cor</th>
                        <th>2° Cor</th>
                        <th>Desaparecimento</th>
                        <th width="270">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pets as $item)
                        <tr>
                            <td><img width="30px" height="30px" src="{{ $item->photo }}" alt="{{ $item->photo }}"></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->species }}</td>
                            <td>{{ $item->sex }}</td>
                            <td>{{ $item->breed }}</td>
                            <td>{{ $item->size }}</td>
                            <td>{{ $item->predominant_color }}</td>
                            <td>{{ $item->secondary_color }}</td>
                            <td>{{ $item->date_disappearance }}</td>
                            <td style="width=10px;">
                                <a href="{{ route('pets.edit', $item->id) }}" class="btn btn-info">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="{{ route('pets.show', $item->id) }}" class="btn btn-warning">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <a href="{{ route('pets.lost.found', $item->id) }}" class="btn btn-primary">
                                    <i class="fas fa-chart-line"></i>
                                </a>
                                <a href="{{ route('pets.lost.found', $item->id) }}" class="btn btn-success">
                                    <i class="fas fa-search-location"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer" style="display: flex; justify-content: center;">
            {!! $pets->links() !!}
        </div>
    </div>
@stop
