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
                            Date
                        </div>
                        <div class="col-md-3">
                            <input type="time" name="start_time" class="form-control" value="{{ request('start_time') }}" step="1">Start Time
                        </div>
                        <div class="col-md-3">
                            <input type="time" name="end_time" class="form-control" value="{{ request('end_time') }}" step="1"> End Time
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>

                <!-- Show all data in scrollable table -->
                <div id="all-data-scrollable" style="overflow-y: auto; max-height: 400px; display: block; margin-top: 20px;">
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
                                    <td>{{ $waterLevel->level }} Meter</td>
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
        function formatTimeToWIB(dateString) {
            const date = new Date(dateString);
            const options = {
                timeZone: 'Asia/Jakarta',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            return date.toLocaleTimeString('id-ID', options);
        }

        document.addEventListener('DOMContentLoaded', () => {
            const timeElements = document.querySelectorAll('td[data-time]');
            timeElements.forEach(el => {
                const originalTime = el.getAttribute('data-time');
                const formattedTime = formatTimeToWIB(originalTime);
                el.textContent = formattedTime;
            });
        });
    </script>
@endsection