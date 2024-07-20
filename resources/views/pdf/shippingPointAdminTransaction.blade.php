<!DOCTYPE html>
<html>

<head>
    <title>Report Transactions</title>
    <style>
        /* Apply styles to the table */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Apply styles to table headers (th) */
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            border: 1px solid #dddddd;
        }

        /* Apply alternating row background colors */
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Apply styles to table data cells (td) */
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
    </style>
    <!-- Add any CSS styling here -->
</head>

<body>
    <p style="text-align: left">Shipping Point: {{ $rechargeBalances[0]->shipping_point->full_name }}</p>
    <p style="text-align: left">Email: {{ $rechargeBalances[0]->shipping_point->email }}</p>
    <p style="text-align: left">Phone Number: {{ $rechargeBalances[0]->shipping_point->phone_number }}</p>
    <p style="text-align: left">Address: {{ $rechargeBalances[0]->shipping_point->address }}</p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Employee Name
                </th>

                <th>
                    User
                </th>
                <th>
                    Balance
                </th>
                <th>
                    Date & Time
                </th>
            </tr>
        </thead>
        <tbody>

            @foreach ($rechargeBalances as $rechargeBalance)
                <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">
                    <td class="table-td">{{ $loop->index + 1 }}</td>
                    <td class="table-td">{{ $rechargeBalance->employee_name }}</td>
                    <td class="table-td ">{{ $rechargeBalance->user->phone_number }}</td>
                    <td class="table-td ">{{ $rechargeBalance->balance }}</td>
                    <td class="table-td ">{{ $rechargeBalance->created_at }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <p>Total Shipping Balance: {{ $total }}</p>
</body>

</html>
