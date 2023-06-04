<!-- reports.index.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>User Timelog Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>User Report</h1>
    {{-- @dd($reports); --}}
    @foreach($reports as $report)
    {{-- @dd($report); --}}
        <p>User: {{ $report['user_name'] }}</p>
        <p>Project: {{ $report['project'] }}</p>
        <p>Client: {{ $report['client'] }}</p>

        {{-- <p>Start Date: {{ $start_date->toDateString() }}</p>
        <p>End Date: {{ $end_date->toDateString() }}</p> --}}

        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    {{-- <th>Date</th> --}}
                    {{-- <th>Project</th> --}}
                    <th>Total Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($report['activities'] as $item)
                <tr>
                    <td>{{ $item['activity'] }}</td>
                    {{-- <td>{{ $item['date'] }}</td> --}}
                    {{-- <td>{{ $item['project'] }}</td> --}}
                    <td>{{ $item['total_time'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
    @endforeach
    
</body>
</html>
