<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Run List</title>
    <style>
        @media print {
            body {
                font-family: system-ui;
                margin: 10mm 10mm;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            th,
            td {
                padding: 8px;
                border: 1px solid #dad4d9;
                text-align: left;
                font-weight: 300;
            }

            th {
                background-color: #ffffff;
            }
        }

        /* Additional styles for the screen view (optional) */
        body {
            font-family: system-ui;
            margin: 10mm 10mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #dad4d9;
            text-align: left;
            font-weight: 300;
        }

        th {
            background-color: #ffffff;
        }
    </style>
</head>

<body class="py-4">
    <main>
        <div class="container">
            <table class="table table-striped">
                <thead>
                    <tr>

                        <th scope="col">Description</th>
                        <th scope="col">Item Number</th>
                        <th scope="col">Claim Number</th>
                        <th scope="col">Lot Number</th>
                        <th scope="col"># of Runs</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($run_lists as $run_list)
                        <tr>
                            <th>{{ $run_list->description }}</th>
                            <th>{{ $run_list->item_number }}</th>
                            <th>{{ $run_list->claim_number }}</th>
                            <th>{{ $run_list->lot_number }}</th>
                            <th>{{ $run_list->number_of_runs }}</th>
                        </tr>
                    @endforeach


                </tbody>
            </table>

    </main>

</body>

</html>
