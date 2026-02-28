<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribers Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #111827;
            margin-bottom: 5px;
        }
        p {
            text-align: center;
            color: #6b7280;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #d1d5db;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .status-active {
            color: #166534;
            font-weight: bold;
        }
        .status-unsubscribed {
            color: #991b1b;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h1>Subscribers Directory</h1>
    <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email Address</th>
                <th>Subscribed At</th>
                <th>Unsubscribed At</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscribers as $sub)
                <tr>
                    <td>{{ $sub->id }}</td>
                    <td>{{ $sub->email }}</td>
                    <td>{{ $sub->created_at ? $sub->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                    <td>{{ $sub->unsubscribed_at ? $sub->unsubscribed_at->format('Y-m-d H:i') : 'N/A' }}</td>
                    <td>
                        @if($sub->unsubscribed_at)
                            <span class="status-unsubscribed">Unsubscribed</span>
                        @else
                            <span class="status-active">Active</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>