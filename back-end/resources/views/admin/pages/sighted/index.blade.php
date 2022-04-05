@extends('adminlte::page')

@section('title', 'Avistados')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Dashboard</a></li>
        <li class="breadcrumb-item active"><a class="breadcrumb-item active" href="{{ route('sighted.index') }}"
                class="active">Avistados</a></li>
    </ol>
    <div style="display: flex; justify-content: space-between">
        <h1>Avistados</h1>
    </div>

@stop

@section('content')
    <div class="card">
        <div class="card-header">
        </div>
        <div class="card-body">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Dono</th>
                        <th>Espécie</th>
                        <th>Desaparecimento</th>
                        <th>Status</th>
                        <th width="270">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $item)
                        <tr>
                            <td>
                                <img width="30px" height="30px" src="{{ $item->photo }}" alt="{{ $item->photo }}">
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->dono }}</td>
                            <td>{{ $item->species }}</td>
                            <td>{{ date('d/m/Y', strtotime($item->date_disappearance)) }}</td>
                            @if ($item->status_id == 1)
                                <td class="text-danger">{{ $item->status }}</td>
                            @else
                                <td class="text-success">{{ $item->status }}</td>
                            @endif
                            <td style="width=10px;">
                                <form action="{{ route('sighted.show', $item->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" title="AVISTAMENTOS">
                                        {{ $item->count }} <i class="fa fa-arrow-right" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer" style="display: flex; justify-content: center;">
        </div>
    </div>
@stop
