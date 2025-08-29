<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>
    <h2>Notes — {{ $exam->title }}</h2>
    <table>
        <thead>
            <tr>
                <th>Étudiant</th>
                <th>Score</th>
                <th>Max</th>
                <th>%</
                        th>
                <th>Soumis</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attempts as $a)
            <tr>
                <td>{{ $a->user->name }}</td>
                <td>{{ $a->score }}</td>
                <td>{{ $a->max_score }}</td>
                <td>{{ $a->max_score ? round(100*$a->score/$a->max_score,1) : 0 }}%</
                        td>
                <td>{{ optional($a->submitted_at)->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>