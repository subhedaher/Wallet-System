@extends('cms.parent')

@section('title', 'Payment Providers')
@section('main-title', 'Payment Providers')

@section('content')
    <div class="card">
        <div class="card-body flex flex-col p-6">
            <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">Edit Payment Provider</div>
                </div>
            </header>
            <div class="card-text h-full ">
                <form class="space-y-4" id="form-data">
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Name</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Name" id="name"
                                value="{{ $paymentProvider->name }}">
                            <iconify-icon icon="mdi:rename-box-outline"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Phone</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Phone Number" id="phoneNumber"
                                value="{{ $paymentProvider->phone_number }}">
                            <iconify-icon icon="heroicons-outline:phone"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area">
                        <label for="select" class="form-label">Payment Category</label>
                        <select id="paymentCategory" class="form-control">
                            <option value=""></option>
                            @foreach ($paymentCategories as $paymentCategory)
                                <option value="{{ $paymentCategory->id }}" @selected($paymentProvider->payment_category_id == $paymentCategory->id)
                                    class="dark:bg-slate-700">
                                    {{ $paymentCategory->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="input-area">
                        <div class="filePreview">
                            <label for="largeInput" class="form-label">Image</label>
                            <label>
                                <input type="file" class=" w-full hidden" id="image">
                                <span class="w-full h-[40px] file-control flex items-center custom-class">
                                    <span class="flex-1 overflow-hidden text-ellipsis whitespace-nowrap">
                                        <span id="placeholder" class="text-slate-400">Choose a image
                                            here...</span>
                                    </span>
                                    <span
                                        class="file-name flex-none cursor-pointer border-l px-4 border-slate-200 dark:border-slate-700 h-full inline-flex items-center bg-slate-100 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm rounded-tr rounded-br font-normal">Browse</span>
                                </span>
                            </label>
                            <div id="file-preview">
                                <img src="{{ Storage::url($paymentProvider->image) }}" alt="">
                            </div>
                        </div>
                    </div>

                    <input type="button" class="btn inline-flex justify-center btn-dark"
                        onclick="editPaymentProvider('{{ route('payment-providers.update', $paymentProvider->id) }}')"
                        value="Save">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function editPaymentProvider(route) {
            let x = document.getElementById('image').files[0];
            let formData = new FormData();
            formData.append('_method', 'put');
            formData.append('name', document.getElementById('name').value);
            formData.append('phoneNumber', document.getElementById('phoneNumber').value);
            formData.append('paymentCategory', document.getElementById('paymentCategory').value);
            if (x == undefined) {
                formData.append('image', '');
            } else {
                formData.append('image', document.getElementById('image').files[0]);
            }
            axios.post(route, formData)
                .then(function(response) {
                    toastr.success(response.data.message);
                    window.location.href = '{{ route('payment-providers.index') }}';
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
