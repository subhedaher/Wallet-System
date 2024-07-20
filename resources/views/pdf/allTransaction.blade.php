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
    {{-- <p style="text-align: center">{{ $rechargeBalances[0]->shipping_point->full_name }}</p> --}}
    <p style="text-align: center">Shipping Points</p>
    <table>
        <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Shipping Point
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
                    <td class="table-td">{{ $rechargeBalance->shipping_point->full_name }}</td>
                    <td class="table-td">{{ $rechargeBalance->employee_name }}</td>
                    <td class="table-td ">{{ $rechargeBalance->user->phone_number }}</td>
                    <td class="table-td ">{{ $rechargeBalance->balance }}</td>
                    <td class="table-td ">{{ $rechargeBalance->created_at }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <p>Total Balance: {{ $totalRechargeBalances }}</p>
    <hr>
    <p style="text-align: center">Payment providers</p>
    <table>
        <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Payment provider
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
                    <td class="table-td">{{ $payment_of_bill->payment_provider->name }}</td>
                    <td class="table-td">{{ $payment_of_bill->user->phone_number }}</td>
                    <td class="table-td ">{{ $payment_of_bill->balance }}</td>
                    <td class="table-td ">{{ $payment_of_bill->details }}</td>
                    <td class="table-td ">{{ $payment_of_bill->created_at }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <p>Total Balance: {{ $totalPayment_of_bills }}</p>
    <hr>
    <p style="text-align: center">Money Transfers</p>
    <table>
        <thead>
            <tr>
                <th>
                    #
                </th>
                <th>
                    Send User
                </th>
                <th>
                    Receive User
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

            @foreach ($money_transfers as $money_transfer)
                <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">
                    <td class="table-td">{{ $loop->index + 1 }}</td>
                    <td class="table-td ">{{ $money_transfer->sendUser->phone_number }}</td>
                    <td class="table-td ">{{ $money_transfer->receiveUser->phone_number }}</td>
                    <td class="table-td ">{{ $money_transfer->balance }}</td>
                    <td class="table-td ">{{ $money_transfer->details }}</td>
                    <td class="table-td ">{{ $money_transfer->created_at }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <p>Total Balance: {{ $totalMoney_transfers }}</p>
</body>

</html>
