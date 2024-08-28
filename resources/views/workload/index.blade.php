@extends('layouts.app')

@section('workload_content')
<div class="container">
    <h1>Workload Management by Team</h1> </br>


    <!-- Search Bar for Teams -->
    <div class="mb-4">
        <form method="GET" action="{{ route('workload.byTeam') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search Teams" value="{{ $search }}">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>
    @foreach($teams as $team)
        <div class="team-section mb-4 p-4 border rounded" style="background-color: #f8f9fa;">
            <h2 class="text-primary">{{ $team->name }}</h2></br>
            <!-- Task Distribution Table -->
            <div class="mb-4">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Team Member</th>
                            <th>Number of Tasks</th>
                            <th>View Tasks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($team->users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->tasks->where('team_id', $team->id)->whereNull('parent_task')->count() }}</td>
                                <td>
                                    <a href="{{ route('tasks.byUser', ['id' => $user->id, 'team_id' => $team->id]) }}" class="btn btn-info">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Chart for Task Distribution -->
            <h3 class="text-secondary">Task Distribution for the Team</h3>
            <div style="position: relative; width: 100%; max-width: 800px; margin: auto;">
                <canvas id="tasksChart-{{ $team->id }}" width="400" height="200"></canvas>
            </div>
        </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @foreach($teams as $team)
            var ctx = document.getElementById('tasksChart-{{ $team->id }}').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [
                        @foreach($team->users as $user)
                            '{{ $user->name }}',
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Number of Tasks',
                        data: [
                            @foreach($team->users as $user)
                                {{ $user->tasks->where('team_id', $team->id)->count() }},
                            @endforeach
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'Team Members'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Tasks'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw + ' tasks';
                                }
                            }
                        }
                    }
                }
            });
        @endforeach
    });
</script>
@endsection
