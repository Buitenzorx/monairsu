@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container" style="text-align: center; margin-top: 10px;">
        <!-- Logo and Description -->
        <img src="{{ asset('monairsu/public/images/logo-kpspams.png') }}" style="width: 120px;">
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
            // Update data every second
            setInterval(function() {
                $.getJSON("/monairsu/public/api/water-level", function(data) {
                    $("#JARAK").text(data.level);
                    $("#STATUS-JARAK").text(data.status);
                });
            }, 1000);

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

            // Function to update the chart
            // ...

            // Function to update the chart
            function updateChart() {
                $.getJSON("/monairsu/public/api/water-level-data", function(data) {
                    var labels = [];
                    var values = [];
                    data.forEach(function(entry) {
                        labels.push(entry.time); // Format time to WIB
                        values.push(entry.level);
                    });

                    // Batasi jumlah data yang ditampilkan pada grafik
                    if (waterLevelChart.data.labels.length >= 15) { // 10 menit
                        waterLevelChart.data.labels.shift();
                        waterLevelChart.data.datasets[0].data.shift();
                    }

                    waterLevelChart.data.labels.push(labels[labels.length - 1]);
                    waterLevelChart.data.datasets[0].data.push(values[values.length - 1]);
                    waterLevelChart.update();
                });
            }


            updateChart(); // Initial load of chart
            setInterval(updateChart, 1000); // Update chart every minute
        });
    </script>
@endsection
