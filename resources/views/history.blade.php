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
                        <div class="col-md-4">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-4">
                            <input type="time" name="time" class="form-control" value="{{ request('time') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div style="overflow-x: auto;">
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
                            @foreach($displayedLevels as $waterLevel)
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

                <!-- Scrollable Table for All Data -->
                <div class="mt-3">
                    <button class="btn btn-secondary" id="loadMore">Load More</button>
                </div>
                <div id="scrollable-data" style="overflow-y: auto; max-height: 400px; display: none;">
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

    <script>
        document.getElementById('loadMore').addEventListener('click', function() {
            var scrollableData = document.getElementById('scrollable-data');
            scrollableData.style.display = scrollableData.style.display === 'none' ? 'block' : 'none';
        });
    </script>
@endsection
