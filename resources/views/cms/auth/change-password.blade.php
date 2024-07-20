@extends('cms.parent')

@section('title', 'Edit Password')
@section('main-title', 'Edit Password')

@section('content')
    <div class="card">
        <div class="card-body flex flex-col p-6">
            <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">Edit Password</div>
                </div>
            </header>
            <div class="card-text h-full ">
                <form class="space-y-4" id="form-data">
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">Old Password</label>
                        <div class="relative">
                            <input type="password" class="form-control !pl-9" placeholder="Old Password" id="oldPassword">
                            <iconify-icon icon="heroicons-outline:lock-closed"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">New Password</label>
                        <div class="relative">
                            <input type="password" class="form-control !pl-9"
                                placeholder="8+ characters, 1 capitat letter, 1 symbol" id="newPassword">
                            <iconify-icon icon="heroicons-outline:lock-closed"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <div class="input-area relative">
                        <label for="largeInput" class="form-label">New Password Confirmation</label>
                        <div class="relative">
                            <input type="password" class="form-control !pl-9" placeholder="Password Confirmation"
                                id="newPasswordConfirmation">
                            <iconify-icon icon="heroicons-outline:lock-closed"
                                class="absolute left-2 top-1/2 -translate-y-1/2 text-base text-slate-500"></iconify-icon>
                        </div>
                    </div>
                    <input type="button" class="btn inline-flex justify-center btn-dark" onclick="updatePassword()"
                        value="Save" style="cursor: pointer">
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updatePassword() {
            axios.put('{{ route('auth.update') }}', {
                'old-password': document.getElementById('oldPassword').value,
                'new-password': document.getElementById('newPassword').value,
                'new-password_confirmation': document.getElementById('newPasswordConfirmation').value
            }).then(function(response) {
                toastr.success(response.data.message);
                document.getElementById('form-data').reset();
            }).catch(function(error) {
                toastr.error(error.response.data.message);
            });
        }
    </script>
@endsection
