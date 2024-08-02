@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container" style="text-align: center; margin-top: 10px;">
        <!-- Logo and Description -->
        <img src="{{ asset('monairsu/public/img/logo-kpspams.png') }}" style="width: 120px;">
        <p style="font-size: 50px; font-weight: bold; color: white;">PAM SIMAS SAGARA</p>
        <p style="font-size: 20px; font-weight: bold; color: white;">-DESA SINDANGKERTA, KECAMATAN SINDANGKERTA, KABUPATEN
            BANDUNG BARAT, JAWA BARAT-</p>

        <div class="row">
            <!-- Grafik -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"
                        style="font-size: 30px; font-weight: bold; background-color: cornflowerblue; color: white;">
                        <h3>Grafik Tinggi Air Terhadap Waktu</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="waterLevelChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Data Sensor -->
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-header"
                        style="font-size: 30px; font-weight: bold; background-color: cornflowerblue; color: white;">
                        <h3>NILAI JARAK</h3>
                    </div>
                    <div class="card-body" id="JARAK" style="font-size: 30px; font-weight: bold;">
                        <h1><span id="nilai_jarak">0</span></h1>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"
                        style="font-size: 30px; font-weight: bold; background-color: cornflowerblue; color: white;">
                        <h3>Status</h3>
                    </div>
                    <div class="card-body" id="STATUS-JARAK" style="font-size: 20px; font-weight: bold;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            var lastValue = null;

            // Initialize the chart
            var ctx = document.getElementById('waterLevelChart').getContext('2d');
            var waterLevelChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [], // Time labels will be added dynamically
                    datasets: [{
                        label: 'Nilai Jarak',
                        borderColor: 'cornflowerblue',
                        backgroundColor: 'rgba(100, 149, 237, 0.2)',
                        data: [] // Data values will be added dynamically
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Waktu (WIB)'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Nilai Jarak'
                            }
                        }
                    }
                }
            });

            // Fetch the initial data
            $.getJSON("monairsu/public/api/water-level-data", function(data) {
                data.forEach(function(entry) {
                    waterLevelChart.data.labels.push(entry.time);
                    waterLevelChart.data.datasets[0].data.push(entry.level);
                });
                waterLevelChart.update();
            });

            // Update data every second
            setInterval(function() {
                $.getJSON("monairsu/public/api/water-level", function(data) {
                    if (lastValue !== data.level) {
                        $("#nilai_jarak").text(data.level);
                        $("#STATUS-JARAK").text(data.status);
                        lastValue = data.level;
                        updateChart(data);
                    }
                });
            }, 1000);

            // Function to update the chart
            function updateChart(data) {
                var time = new Date().toLocaleTimeString();
                var level = data.level;

                if (waterLevelChart.data.labels.length >= 15) { // Limit data points to 15
                    waterLevelChart.data.labels.shift();
                    waterLevelChart.data.datasets[0].data.shift();
                }

                waterLevelChart.data.labels.push(time);
                waterLevelChart.data.datasets[0].data.push(level);
                waterLevelChart.update();
            }
        });
    </script>
@endsection
