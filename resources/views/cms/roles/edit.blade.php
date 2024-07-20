@extends('cms.parent')

@section('title', 'Roles')
@section('main-title', 'Roles')

@section('content')
    <div class="card">
        <div class="card-body flex flex-col p-6">
            <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">Edit Role</div>
                </div>
            </header>
            <div class="card-text h-full ">
                <form class="space-y-4" id="form-data">
                    <div class="input-area">
                        <label for="select" class="form-label">Type</label>
                        <select id="guard" class="form-control">
                            <option value=""></option>
                            <option value="admin" @selected($role->guard_name === 'admin') class="dark:bg-slate-700">Admin</option>
                            <option value="shippingPoint" @selected($role->guard_name === 'shippingPoint') class="dark:bg-slate-700">Shipping
                                Point</option>
                        </select>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Name</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Name" id="name"
                                value="{{ $role->name }}">
                            <iconify-icon icon="mdi:rename-box-outline"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <input type="button" class="btn inline-flex justify-center btn-dark"
                        onclick="EditRole('{{ route('roles.update', $role->id) }}')" value="Save" style="cursor: pointer">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function EditRole(route) {
            axios.put(route, {
                name: document.getElementById('name').value,
                guard: document.getElementById('guard').value,
            }).then(function(response) {
                toastr.success(response.data.message);
                window.location.href = '{{ route('roles.index') }}';
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
