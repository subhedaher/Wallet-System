@extends('cms.parent')

@section('title', 'Transactions')
@section('main-title', 'Shipping Point Transactions')

@section('button')
    <button
        class="btn leading-0 inline-flex justify-center bg-white text-slate-700 dark:bg-slate-800 dark:text-slate-300 !font-normal">
        <span class="flex items-center">
            <iconify-icon class="text-xl ltr:mr-2 rtl:ml-2 font-light" icon="fa6-solid:file-pdf"></iconify-icon>
            <a href="{{ route('shippingPointReport.transaction') }}">Daily Report</a>
        </span>
    </button>
@endsection

@section('content')

    <div id="content_layout">
        <div class="grid xxl:grid-cols-2 grid-cols-1 gap-9">
            <!-- BEGIN: Hover Tables -->
            <div class="card">
                <header class=" card-header noborder">
                    <h4 class="card-title">Shipping Transactions
                    </h4>
                </header>
                <div class="card-body px-6 pb-6">
                    <div class="overflow-x-auto -mx-6">
                        <div class="inline-block min-w-full align-middle">
                            <div class="overflow-hidden ">
                                <table class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700">
                                    <thead class="bg-slate-200 dark:bg-slate-700">
                                        <tr>
                                            <th scope="col" class=" table-th ">
                                                #
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Employee Name
                                            </th>

                                            <th scope="col" class=" table-th ">
                                                User
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Balance
                                            </th>
                                            <th scope="col" class=" table-th ">
                                                Date & Time
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">

                                        @foreach ($transactions as $transaction)
                                            <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">
                                                <td class="table-td">{{ $loop->index + 1 }}</td>
                                                <td class="table-td">{{ $transaction->employee_name }}</td>
                                                <td class="table-td ">{{ $transaction->user->phone_number }}</td>
                                                <td class="table-td ">{{ $transaction->balance }}</td>
                                                <td class="table-td ">{{ $transaction->created_at }}</td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Hover Tables -->
        </div>

    </div>
@endsection
