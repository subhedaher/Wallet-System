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
    <p style="text-align: left">Payment Provider: {{ $payment_of_bills[0]->payment_provider->name }}</p>
    <p style="text-align: left">Phone Number: {{ $payment_of_bills[0]->payment_provider->phone_number }}</p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    User
                </th>
                <th>
                    Balance
                </th>
                <th>
                    Details
                </th>
                <th>
                    Date & Time
                </th>
            </tr>
        </thead>
        <tbody>

            @foreach ($payment_of_bills as $payment_of_bill)
                <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">
                    <td class="table-td">{{ $loop->index + 1 }}</td>
                    <td class="table-td">{{ $payment_of_bill->user->phone_number }}</td>
                    <td class="table-td ">{{ $payment_of_bill->balance }}</td>
                    <td class="table-td ">{{ $payment_of_bill->details }}</td>
                    <td class="table-td ">{{ $payment_of_bill->created_at }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <p>Total Balance: {{ $total }}</p>
</body>

</html>
