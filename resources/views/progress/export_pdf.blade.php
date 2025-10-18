<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Workout Progress Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            font-size: 13px;
            color: #333;
            margin: 20px;
            background: #FFF8E6; /* near-white warm background */
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #FF5C00; /* deep orange/red-orange */
            font-size: 24px;
            letter-spacing: 1px;
        }

        .user-info {
            margin-bottom: 25px;
            padding: 15px;
            border-radius: 8px;
            background: #FF8728; /* bright orange */
            border: 1px solid #FF5C00; /* deep orange border */
            color: #fff;
        }

        .user-info p {
            margin: 4px 0;
            font-size: 13px;
        }

        .user-info strong {
            width: 100px;
            display: inline-block;
            color: #FFC37D; /* light orange highlight */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        table, th, td {
            border: 1px solid #FF8728; /* bright orange border */
        }

        th, td {
            padding: 8px 6px;
            text-align: center;
        }

        th {
            background: #FF5C00; /* deep orange */
            color: #FFF8E6; /* near-white text */
            font-weight: 500;
        }

        tbody tr:nth-child(even) {
            background: #FFC37D; /* light orange for even rows */
        }

        .workout-table {
            width: 100%;
            margin-top: 5px;
            border: 1px solid #FF8728;
        }

        .workout-table th {
            background: #FF8728; /* bright orange */
            color: #FFF8E6; /* near-white */
            font-size: 12px;
            padding: 4px;
        }

        .workout-table td {
            font-size: 12px;
            padding: 4px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 11px;
            color: #FF5C00; /* deep orange */
        }
    </style>
</head>

<body>
    <h1>Workout Progress Report</h1>

    <!-- User Info Section -->
    <div class="user-info">
        <p><strong>Name:</strong> {{ $user->name }}</p>
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
                <th>Workout Details</th>
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
