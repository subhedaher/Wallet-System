@extends('cms.parent')

@section('title', 'Edit Profile')
@section('main-title', 'Edit Profile')

@section('content')
    <div class="card">
        <div class="card-body flex flex-col p-6">
            <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">Edit profile</div>
                </div>
            </header>
            <div class="card-text h-full ">
                <form class="space-y-4" id="form-data">
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Full Name</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Full Name" id="fullName"
                                value="{{ $user->full_name }}">
                            <iconify-icon icon="heroicons-outline:user"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Phone</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Phone Number" id="phoneNumber"
                                value="{{ $user->phone_number }}">
                            <iconify-icon icon="heroicons-outline:phone"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    @if (session('guard') === 'shippingPoint')
                        <div class="input-area relative">
                            <label for="largeInput" class="form-label">Address</label>
                            <div class="relative">
                                <input type="text" class="form-control !pl-9" placeholder="Address" id="address"
                                    value="{{ $user->address }}" value="{{ $user->address }}">
                                <iconify-icon icon="mdi:address-marker"
                                    class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                            </div>
                        </div>
                    @endif
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Email</label>
                        <div class="relative">
                            <input type="email" class="form-control !pl-9" placeholder="Your Email" id="email"
                                value="{{ $user->email }}">
                            <iconify-icon icon="heroicons-outline:mail"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    @if (session('guard') === 'admin')
                        <input type="button" class="btn inline-flex justify-center btn-dark" onclick="updateAdminProfile()"
                            value="Save" style="cursor: pointer">
                    @else
                        <input type="button" class="btn inline-flex justify-center btn-dark"
                            onclick="updateShippingPointProfile()" value="Save" style="cursor: pointer">
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateAdminProfile() {
            axios.put('{{ route('auth.updateUser') }}', {
                full_name: document.getElementById('fullName').value,
                phone_number: document.getElementById('phoneNumber').value,
                email: document.getElementById('email').value,
            }).then(function(response) {
                toastr.success(response.data.message);
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }

        function updateShippingPointProfile() {
            let x = document.getElementById('address').value;
            axios.put('{{ route('auth.updateUser') }}', {
                full_name: document.getElementById('fullName').value,
                phone_number: document.getElementById('phoneNumber').value,
                address: document.getElementById('address').value,
                email: document.getElementById('email').value,
            }).then(function(response) {
                toastr.success(response.data.message);
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
