@extends('cms.parent')

@section('title', 'Transactions')
@section('main-title', 'Payment Provider Transactions')

@section('content')
    <div class="card">
        <div class="card-body flex flex-col p-6">
            <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">Report Payment Provider</div>
                </div>
            </header>
            <div class="card-text h-full ">
                <form class="space-y-4" id="form-data">
                    <div class="input-area">
                        <label for="select" class="form-label">Payment Provider</label>
                        <select id="paymentProvider" class="form-control">
                            <option value=""></option>
                            @foreach ($paymentProviders as $paymentProvider)
                                <option value="{{ $paymentProvider->id }}" class="dark:bg-slate-700">
                                    {{ $paymentProvider->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="default-picker" class=" form-label">From</label>
                        <input class="form-control py-2 flatpickr flatpickr-input active" id="from" type="date"
                            value="" readonly="readonly">
                    </div>
                    <div>
                        <label for="default-picker" class=" form-label">To (not Include)</label>
                        <input class="form-control py-2 flatpickr flatpickr-input active" id="to" type="date"
                            value="" readonly="readonly">
                    </div>

                    <input type="button" class="btn inline-flex justify-center btn-dark" onclick="paymentproviderReport()"
                        value="Report" style="cursor: pointer">
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        function paymentproviderReport() {
            axios.post('{{ route('paymentProviderReport.transaction') }}', {
                paymentProvider: document.getElementById('paymentProvider').value,
                from: document.getElementById('from').value,
                to: document.getElementById('to').value
            }).then(function(response) {
                document.getElementById('form-data').reset();
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
