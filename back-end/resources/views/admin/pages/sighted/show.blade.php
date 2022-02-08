@extends('adminlte::page')

@section('title', "Avistamentos {$pet->name}")

@section('content_header')
    <h1>Avistamentos <b>{{ $pet->name }}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Animal</th>
                        <th>Quem avistou</th>
                        <th>Local</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sighted as $item)
                        <tr>
                            <td>{{ date('d/m/Y', strtotime($item->dataAvistamento)) }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->userAvistou }}</td>
                            <td>{{ $item->localAvistamento }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
