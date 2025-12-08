<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Financial Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #f5f5f5;
            border-bottom: 2px solid #333;
        }
        table th {
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table tfoot {
            border-top: 2px solid #333;
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .summary {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .summary-item {
            flex: 1;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
        }
        .summary-item label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .summary-item .value {
            font-size: 20px;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #999;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Financial Report</h1>
        <p>Thnaya Dental Center</p>
        <p>Report Date: {{ now()->format('M d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th class="text-right">Amount</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData as $item)
            <tr>
                <td>{{ $item['date'] ?? 'N/A' }}</td>
                <td>{{ $item['description'] ?? 'N/A' }}</td>
                <td class="text-right">${{ number_format($item['amount'] ?? 0, 2) }}</td>
                <td>{{ ucfirst($item['type'] ?? 'N/A') }}</td>
                <td>{{ ucfirst($item['status'] ?? 'N/A') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No data available</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
                <td class="text-right">${{ number_format($reportData->sum('amount'), 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Report Generated: {{ now()->format('M d, Y H:i:s') }}</p>
    </div>
</body>
</html>
