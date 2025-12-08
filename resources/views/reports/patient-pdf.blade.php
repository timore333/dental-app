<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Patient Report</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background-color: #f5f5f5;
            border-bottom: 2px solid #333;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Patient Report</h1>
        <p>Thnaya Dental Center</p>
        <p>Report Date: {{ now()->format('M d, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Phone</th>
                <th>Type</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
            <tr>
                <td>{{ $patient->name }}</td>
                <td>{{ $patient->age }}</td>
                <td>{{ ucfirst($patient->gender) }}</td>
                <td>{{ $patient->phone }}</td>
                <td>{{ ucfirst($patient->patient_type) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No patients found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
