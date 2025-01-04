@extends('layouts.sidebar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Header Cards -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Plays</h5>
                    <h3>{{ $totalPlays }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Clicks</h5>
                    <h3>{{ $totalClicks }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Most Played Song</h5>
                    <h3>{{ $mostPlayedSong }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Line Chart: Plays Over Time -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5>Plays Over Time</h5>
                    <canvas id="playsOverTimeChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Pie Chart: Plays Distribution -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Plays Distribution</h5>
                    <canvas id="playsDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Detailed Table -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5>Detailed Song Analytics</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Song Name</th>
                                <th>Plays</th>
                                <th>Clicks</th>
                                <th>Conversions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($songs as $song)
                            <tr>
                                <td>{{ $song['name'] }}</td>
                                <td>{{ $song['plays'] }}</td>
                                <td>{{ $song['clicks'] }}</td>
                                <td>{{ $song['conversions'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Heatmap: Plays per Hour -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5>Plays by Hour</h5>
                    <canvas id="playsByHourChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Plays Over Time
    const playsOverTimeCtx = document.getElementById('playsOverTimeChart').getContext('2d');
    new Chart(playsOverTimeCtx, {
        type: 'line',
        data: {
            labels: @json($timeLabels),
            datasets: [{
                label: 'Plays',
                data: @json($timeData),
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }]
        }
    });

    // Plays Distribution
    const playsDistributionCtx = document.getElementById('playsDistributionChart').getContext('2d');
    new Chart(playsDistributionCtx, {
        type: 'pie',
        data: {
            labels: @json($songNames),
            datasets: [{
                data: @json($playCounts),
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
        }
    });

    // Plays by Hour
    const playsByHourCtx = document.getElementById('playsByHourChart').getContext('2d');
    new Chart(playsByHourCtx, {
        type: 'heatmap', // Consider using a library like heatmap.js
        data: { /* Your heatmap data */ }
    });
</script>
@endpush
