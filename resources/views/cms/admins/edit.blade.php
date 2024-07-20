@extends('cms.parent')

@section('title', 'Admins')
@section('main-title', 'Admins')

@section('content')
    <div class="card">
        <div class="card-body flex flex-col p-6">
            <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">Edit Admin</div>
                </div>
            </header>
            <div class="card-text h-full ">
                <form class="space-y-4" id="form-data">
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Full Name</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Full Name" id="fullName"
                                value="{{ $admin->full_name }}">
                            <iconify-icon icon="heroicons-outline:user"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Phone</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Phone Number" id="phoneNumber"
                                value="{{ $admin->phone_number }}">
                            <iconify-icon icon="heroicons-outline:phone"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Email</label>
                        <div class="relative">
                            <input type="email" class="form-control !pl-9" placeholder="Your Email" id="email"
                                value="{{ $admin->email }}">
                            <iconify-icon icon="heroicons-outline:mail"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>

                    </div>
                    <div class="input-area relative">
                        <label for="select" class="form-label">Role</label>
                        <select id="role" class="form-control">
                            <option value=""></option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" @selected($admin->roles[0]->id == $role->id) class="dark:bg-slate-700">
                                    {{ $role->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <input type="button" class="btn inline-flex justify-center btn-dark"
                        onclick="editAdmin('{{ route('admins.update', $admin->id) }}')" value="Save">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function editAdmin(route) {
            axios.put(route, {
                fullName: document.getElementById('fullName').value,
                phoneNumber: document.getElementById('phoneNumber').value,
                email: document.getElementById('email').value,
                role: document.getElementById('role').value
            }).then(function(response) {
                toastr.success(response.data.message);
                window.location.href = '{{ route('admins.index') }}';
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
