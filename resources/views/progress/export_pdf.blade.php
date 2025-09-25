<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Progress Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table,
        th,
        td {
            border: 1px solid #444;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        th {
            background: #FF8C00;
            color: #fff;
        }
    </style>
</head>

<body>
    <h2>Workout Progress Report</h2>

    <div class="user-info">
        <strong>User Name:</strong> {{ $user->name }} <br>
        <strong>Email:</strong> {{ $user->email }} <br>
        <strong>Gender:</strong> {{ config('constant.gender.' . $user->gender) }} <br>
        {{-- <strong>Generated On:</strong> {{ now()->format('d M Y H:i') }} <br> --}}
        <strong>Height:</strong> {{ $user->height }} <br>
        <strong>Weight:</strong> {{ $user->weight }} <br>

    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Workouts Completed</th>
                <th>Weight (kg)</th>
                <th>Avg Kcal Burned</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($progressList as $progress)
                @php
                    $workoutNames = \App\Models\Workout::whereIn(
                        'id',
                        json_decode($progress->workouts_completed, true) ?? [],
                    )
                        ->pluck('workout_name')
                        ->toArray();
                @endphp
                <tr>
                    <td>{{ $progress->workout_on->format('d M Y') }}</td>
                    <td>{{ implode(', ', $workoutNames) }}</td>
                    <td>{{ $progress->current_weight }}</td>
                    <td>{{ $progress->avg_kcal_burned }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
