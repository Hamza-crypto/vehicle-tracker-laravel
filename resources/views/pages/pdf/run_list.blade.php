<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Run List</title>
    <style>
        @media print {
            body {
                font-family: 'Open Sans', sans-serif;
                margin: 10mm 10mm;
                font-size: 14px;
            }

            .header-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            .header-table th {
                padding: 8px;
                border-bottom: 2px solid #dad4d9;
                text-align: left;
                font-weight: bold;
            }

            .data-row {
                border-bottom: 1px solid #dad4d9;
            }

            .data-cell {
                padding: 8px;
                text-align: left;
                border-bottom: 1px solid #dad4d9;
            }

            h1,
            h2 {
                text-align: center;
                margin: 0;
            }

            h2 {
                font-weight: normal;
                color: #666;
            }
        }

        body {
            font-family: 'Open Sans', sans-serif;
            margin: 10mm 10mm;
            font-size: 14px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-table th {
            padding: 8px;
            border-bottom: 2px solid #dad4d9;
            text-align: left;
            font-weight: bold;
        }

        .data-row {
            border-bottom: 1px solid #dad4d9;
        }

        .data-cell {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #dad4d9;
        }

        h1,
        h2 {
            text-align: center;
            margin: 0;
        }

        h2 {
            font-weight: normal;
            color: #666;
        }
    </style>
</head>

<body class="py-4">
    <main>
        <div class="container">
            <table class="header-table">
                <thead>
                    <tr>
                        <th width="7%">Item #</th>
                        <th width="8%">Lot #</th>
                        <th width="10%">Claim #</th>
                        <th width="35%">Description</th>
                        <th width="10%"># of Runs</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($run_lists as $run_list)
                        <tr class="data-row">
                            <td class="data-cell" width="7%">{{ $run_list->item_number }}</td>
                            <td class="data-cell" width="8%">{{ $run_list->lot_number }}</td>
                            <td class="data-cell" width="10%">{{ $run_list->claim_number }}</td>
                            <td class="data-cell" width="35%">{{ $run_list->description }}</td>
                            <td class="data-cell" width="10%">{{ $run_list->number_of_runs }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </main>
</body>

</html>
