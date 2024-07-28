@extends('layouts.app')

@section('title', 'History')

@section('content')
    <div class="container" style="margin-top: 20px;">
        <div class="card">
            <div class="card-header" style="font-size: 30px; font-weight: bold; background-color: cornflowerblue; color: white;">
                <h3>Data History</h3>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" action="{{ route('history') }}" class="mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="time" name="start_time" class="form-control" value="{{ request('start_time') }}">
                        </div>
                        <div class="col-md-3">
                            <input type="time" name="end_time" class="form-control" value="{{ request('end_time') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <!-- Scrollable Table for All Data -->
                <div id="scrollable-data" style="overflow-y: auto; max-height: 400px; display: block;">
                    <table class="table table-bordered table-striped mt-2">
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
                            @foreach($allLevels as $waterLevel)
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
                </div>
            </div>
        </div>
    </div>
@endsection
