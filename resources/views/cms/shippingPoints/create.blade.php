@extends('cms.parent')

@section('title', 'Shipping Points')
@section('main-title', 'Shipping Points')

@section('content')
    <div class="card">
        <div class="card-body flex flex-col p-6">
            <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">Create Shipping Points</div>
                </div>
            </header>
            <div class="card-text h-full ">
                <form class="space-y-4" id="form-data">
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Name</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Name" id="name">
                            <iconify-icon icon="heroicons-outline:user"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Phone</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Phone Number" id="phoneNumber">
                            <iconify-icon icon="heroicons-outline:phone"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Address</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Address" id="address">
                            <iconify-icon icon="mdi:address-marker"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Email</label>
                        <div class="relative">
                            <input type="email" class="form-control !pl-9" placeholder="Your Email" id="email">
                            <iconify-icon icon="heroicons-outline:mail"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Password</label>
                        <div class="relative">
                            <input type="password" class="form-control !pl-9"
                                placeholder="8+ characters, 1 capitat letter, 1 symbol" id="password">
                            <iconify-icon icon="heroicons-outline:lock-closed"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Password Confirmation</label>
                        <div class="relative">
                            <input type="password" class="form-control !pl-9" placeholder="Password Confirmation"
                                id="passwordConfirmation">
                            <iconify-icon icon="heroicons-outline:lock-closed"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <input type="button" class="btn inline-flex justify-center btn-dark" onclick="addShippingPoints()"
                        value="Add" style="cursor: pointer">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function addShippingPoints() {
            axios.post('{{ route('shipping-points.store') }}', {
                name: document.getElementById('name').value,
                phoneNumber: document.getElementById('phoneNumber').value,
                address: document.getElementById('address').value,
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
                password_confirmation: document.getElementById('passwordConfirmation').value
            }).then(function(response) {
                toastr.success(response.data.message);
                document.getElementById('form-data').reset();
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
