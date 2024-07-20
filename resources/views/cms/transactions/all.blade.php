@extends('cms.parent')

@section('title', 'Transactions')
@section('main-title', 'All Transactions')

@section('content')
    <div class="card">
        <div class="card-body flex flex-col p-6">
            <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">Report All</div>
                </div>
            </header>
            <div class="card-text h-full ">
                <form class="space-y-4" id="form-data">
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
                    <input type="button" class="btn inline-flex justify-center btn-dark" value="Report"
                        style="cursor: pointer" onclick="allReport()">
            </div>
            </form>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        function allReport() {
            console.log('subhe');
            axios.post('{{ route('allReport.transaction') }}', {
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
