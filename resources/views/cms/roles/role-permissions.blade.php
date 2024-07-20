@extends('cms.parent')

@section('title', 'Roles')
@section('main-title', 'Roles')

@section('content')
    <div class=" space-y-5">
        <div class="card">
            <header class=" card-header noborder">
                <h4 class="card-title">Read Role Permissions
                </h4>
            </header>
            <div class="card-body px-6 pb-6">
                <div class="overflow-x-auto -mx-6 dashcode-data-table">
                    <span class=" col-span-8  hidden"></span>
                    <span class="  col-span-4 hidden"></span>
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden ">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper no-footer">
                                <div class="min-w-full">
                                    <table
                                        class="min-w-full divide-y divide-slate-100 table-fixed dark:divide-slate-700 data-table dataTable no-footer"
                                        id="DataTables_Table_0">
                                        <thead class=" bg-slate-200 dark:bg-slate-700">
                                            <tr>
                                                <th scope="col" class="table-th sorting sorting_asc" tabindex="0"
                                                    aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                    aria-sort="ascending"
                                                    aria-label="
                                Id
                            : activate to sort column descending"
                                                    style="width: 100px;">
                                                    #
                                                </th>
                                                <th scope="col" class="table-th sorting" tabindex="0"
                                                    aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                    aria-label="
                            full name
                            : activate to sort column ascending"
                                                    style="width: 150px;">
                                                    name
                                                </th>
                                                <th scope="col" class="table-th sorting" tabindex="0"
                                                    aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                    aria-label="
                        full name
                        : activate to sort column ascending"
                                                    style="width: 150px;">
                                                    User Type
                                                </th>
                                                <th scope="col" class="table-th sorting" tabindex="0"
                                                    aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                    aria-label="
                                Action
                            : activate to sort column ascending"
                                                    style="width: 100px;">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-slate-100 dark:bg-slate-800 dark:divide-slate-700">
                                            @foreach ($permissions as $permission)
                                                <tr class="hover:bg-slate-200 dark:hover:bg-slate-700">
                                                    <td class="table-td sorting_1">{{ $loop->index + 1 }}</td>
                                                    <td class="table-td ">{{ $permission->name }}</td>
                                                    <td class="table-td ">{{ $permission->guard_name }}</td>
                                                    <td class="table-td ">
                                                        <div class="flex space-x-3 rtl:space-x-reverse">
                                                            <div class="checkbox-area">
                                                                <label class="inline-flex items-center cursor-pointer">
                                                                    <input type="checkbox" class="hidden" id="permissions_{{$permission->id}}" @checked($permission->assigned)
                                                                        onchange="updateRolePermission({{ $permission->id }})">
                                                                    <span
                                                                        class="h-4 w-4 border flex-none border-slate-100 dark:border-slate-800 rounded inline-flex ltr:mr-3 rtl:ml-3 relative transition-all duration-150 bg-slate-100 dark:bg-slate-900">
                                                                        <img src="{{ asset('assets/images/icon/ck-white.svg') }}"
                                                                            alt=""
                                                                            class="h-[10px] w-[10px] block m-auto opacity-0"></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateRolePermission(id) {
            axios.put('{{ route('roles.updateRolePermission') }}', {
                    role_id: '{{ $role->id }}',
                    permission_id: id
                })
                .then(function(response) {
                    toastr.success(response.data.message);
                }).catch(function(error) {
                    toastr.error(error.response.data.message);
                });
        }
    </script>
@endsection
