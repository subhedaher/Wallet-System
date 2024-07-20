@extends('cms.parent')

@section('title', 'Recharge Balance')
@section('main-title', 'Recharge Balance')

@section('content')
    <div class="lg:col-span-4 col-span-12 mb-5">
        <div class="card h-full">
            <header class="card-header">
                <h4 class="card-title">User Info</h4>
            </header>
            <div class="card-body p-6">
                <ul class="list space-y-8">
                    <li class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                            <iconify-icon icon="icon-park-solid:edit-name"></iconify-icon>
                        </div>
                        <div class="flex-1">
                            <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                full name
                            </div>
                            <a href="mailto:someone@example.com" class="text-base text-slate-600 dark:text-slate-50">
                                {{ $user->full_name }}
                            </a>
                        </div>
                    </li>
                    <li class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                            <iconify-icon icon="heroicons:envelope"></iconify-icon>
                        </div>
                        <div class="flex-1">
                            <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                EMAIL
                            </div>
                            <a href="mailto:someone@example.com" class="text-base text-slate-600 dark:text-slate-50">
                                {{ $user->email }}
                            </a>
                        </div>
                    </li>
                    <li class="flex space-x-3 rtl:space-x-reverse">
                        <div class="flex-none text-2xl text-slate-600 dark:text-slate-300">
                            <iconify-icon icon="mdi:id-card-outline"></iconify-icon>
                        </div>
                        <div class="flex-1">
                            <div class="uppercase text-xs text-slate-500 dark:text-slate-300 mb-1 leading-[12px]">
                                id number
                            </div>
                            <a href="mailto:someone@example.com" class="text-base text-slate-600 dark:text-slate-50">
                                {{ $user->id_number }}
                            </a>
                        </div>
                    </li>
                    <!-- end single list -->
                </ul>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body flex flex-col p-6">
            <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">Recharge Balance</div>
                </div>
            </header>
            <div class="card-text h-full ">
                <form class="space-y-4" id="form-data">
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Employee Name</label>
                        <div class="relative">
                            <input type="text" class="form-control !pl-9" placeholder="Name" id="name">
                            <iconify-icon icon="mdi:rename-box-outline"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Balance</label>
                        <div class="relative">
                            <input type="number" class="form-control !pl-9" placeholder="Balance" id="balance">
                            <iconify-icon icon="mdi:rename-box-outline"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <input type="button" onclick="shipping('{{ route('user.shipping', $user->id) }}')"
                        class="btn inline-flex justify-center btn-dark" value="Send" style="cursor: pointer">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function shipping(route) {
            axios.post(route, {
                name: document.getElementById('name').value,
                balance: document.getElementById('balance').value,
            }).then(function(response) {
                toastr.success(response.data.message);
                document.getElementById('form-data').reset();
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
