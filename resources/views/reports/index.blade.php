<!DOCTYPE html>
<html>
    <head>
        <title>User Timelog Report</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            h1 {
                text-align: center;
                color: #333;
                margin-top: 20px;
                margin-bottom: 5px;
            }
            h3 {
                color: #444;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            th, td {
                padding: 10px;
                border: 1px solid #ddd;
                text-align: left;
            }
            th {
                background-color: #FA6731;
                color: #fff;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            p {
                color: #222;
            }
            hr {
                border: none;
                border-top: 1px solid #ddd;
                margin-top: 40px;
                margin-bottom: 20px;
            }
            .date-range {
            text-align: center;
            margin-bottom: 35px;
            }
            .date-range p {
                font-weight: bold;
                color: #666;
            }
        </style>
    </head>
    <body>
        <h1>User Report</h1>
        <div class="date-range">
            <p>From: 1st May 2023 &nbsp; To: 30th May 2023</p>
        </div>

        @foreach($reports as $report)
        <hr>

            <div class="report-section">
                <h3>{{ $report['user_name'] }}</h3>

                <p>Project: <b>{{ $report['project'] }}</b> (<em>{{ $report['total_time'] }}</em>)</p>
                <p>Client: <b>{{ $report['client'] }}</b></p>

                <table>
                    <thead>
                        <tr>
                            <th>Activity</th>
                            <th>Date</th>
                            <th>Total Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($report['activities'] as $activity)
                        <tr>
                            <td>{{ $activity['activity'] }}</td>
                            <td>{{ $activity['date'] }}</td>
                            <td>{{ $activity['total_time'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </body>
</html>
