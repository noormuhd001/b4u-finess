<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Workout Progress Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #FF6600;
            text-transform: uppercase;
            border-bottom: 2px solid #FF6600;
            padding-bottom: 5px;
        }

        .user-info {
            margin-bottom: 25px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 6px;
        }

        .user-info strong {
            display: inline-block;
            width: 120px;
            color: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 11px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 6px;
            text-align: center;
        }

        th {
            background: #FF8C00;
            color: #fff;
        }

        tbody tr:nth-child(even) {
            background: #fdf3e7;
        }

        .workout-table {
            margin-top: 8px;
            border: none;
        }

        .workout-table th {
            background: #FFA500;
            font-size: 10px;
            padding: 4px;
        }

        .workout-table td {
            font-size: 10px;
            padding: 4px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>

<body>
    <h2>Workout Progress Report</h2>

    <!-- User Info Section -->
    <div class="user-info">
        <p><strong>User Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Gender:</strong> {{ config('constant.gender.' . $user->gender) }}</p>
        <p><strong>Height:</strong> {{ $user->height }} cm</p>
        <p><strong>Weight:</strong> {{ $user->weight }} kg</p>
        <p><strong>Goal:</strong> {{ config('constant.goal.' . $user->goal) }}</p>
    </div>

    <!-- Progress Section -->
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
                                @forelse ($progress->logs as $log)
                                    <tr>
                                        <td>{{ $log->workout->workout_name ?? 'N/A' }}</td>
                                        <td>{{ $log->set_number }}</td>
                                        <td>{{ $log->reps }}</td>
                                        <td>{{ $log->weight }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No workout logs available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('d M Y, h:i A') }}
    </div>
</body>

</html>
