@extends('layouts.app')

@section('title', 'History')

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header" style="font-size: 30px; font-weight: bold; background-color: cornflowerblue; color: white;">
                <h3>Data History</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Jarak</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($waterLevels as $waterLevel)
                            <tr>
                                <td>{{ $waterLevel->no }}</td>
                                <td>{{ $waterLevel->tanggal }}</td>
                                <td>{{ $waterLevel->waktu }}</td>
                                <td>{{ $waterLevel->level }}</td>
                                <td>{{ $waterLevel->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $waterLevels->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
