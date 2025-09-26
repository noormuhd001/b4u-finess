<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Workout Progress Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        .user-info { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table, th, td { border: 1px solid #444; }
        th, td { padding: 6px; text-align: center; }
        th { background: #FF8C00; color: #fff; }
        .workout-table { margin-top: 10px; }
        .workout-table th { background: #FFA500; }
    </style>
</head>
<body>
    <h2>Workout Progress Report</h2>

    <div class="user-info">
        <strong>User Name:</strong> {{ $user->name }} <br>
        <strong>Email:</strong> {{ $user->email }} <br>
        <strong>Gender:</strong> {{ config('constant.gender.' . $user->gender) }} <br>
        <strong>Height:</strong> {{ $user->height }} cm <br>
        <strong>Weight:</strong> {{ $user->weight }} kg <br>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Weight (kg)</th>
                <th>Avg Kcal Burned</th>
                <th>Workouts Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($progressList as $progress)
                <tr>
                    <td>{{ $progress->workout_on->format('d M Y') }}</td>
                    <td>{{ $progress->current_weight }}</td>
                    <td>{{ $progress->avg_kcal_burned }}</td>
                    <td>
                        <table class="workout-table">
                            <thead>
                                <tr>
                                    <th>Workout</th>
                                    <th>Set</th>
                                    <th>Reps</th>
                                    <th>Weight (kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($progress->logs as $log)
                                    <tr>
                                        <td>{{ $log->workout->workout_name ?? 'N/A' }}</td>
                                        <td>{{ $log->set_number }}</td>
                                        <td>{{ $log->reps }}</td>
                                        <td>{{ $log->weight }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
