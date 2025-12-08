<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ledger Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .info {
            margin-bottom: 20px;
            display: flex;
            gap: 40px;
        }
        .info-item {
            flex: 1;
        }
        .info-item label {
            font-weight: bold;
            display: block;
            margin-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table thead {
            background-color: #f5f5f5;
            border-bottom: 2px solid #333;
        }
        table th, table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table tfoot {
            border-top: 2px solid #333;
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ledger Report</h1>
        <p>Thnaya Dental Center</p>
    </div>

    <div class="info">
        <div class="info-item">
            <label>Account:</label>
            <div>{{ $accountName ?? 'General Ledger' }}</div>
        </div>
        <div class="info-item">
            <label>Report Date:</label>
            <div>{{ now()->format('M d, Y') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
                <th class="text-right">Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($entries as $entry)
            <tr>
                <td>{{ $entry->transaction_date }}</td>
                <td>{{ $entry->description }}</td>
                <td class="text-right">{{ $entry->type === 'debit' ? '$' . number_format($entry->amount, 2) : '-' }}</td>
                <td class="text-right">{{ $entry->type === 'credit' ? '$' . number_format($entry->amount, 2) : '-' }}</td>
                <td class="text-right">${{ number_format($entry->running_balance, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No entries found</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">TOTAL</td>
                <td class="text-right">${{ number_format($totalDebits, 2) }}</td>
                <td class="text-right">${{ number_format($totalCredits, 2) }}</td>
                <td class="text-right">${{ number_format($totalDebits - $totalCredits, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
