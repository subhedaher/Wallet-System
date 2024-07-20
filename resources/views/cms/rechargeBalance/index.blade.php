@extends('cms.parent')

@section('title', 'Recharge Balances')
@section('main-title', 'Recharge Balances')

@section('content')

    <div class="card">
        <div class="card-body flex flex-col p-6">
            <header class="flex mb-5 items-center border-b border-slate-100 dark:border-slate-700 pb-5 -mx-6 px-6">
                <div class="flex-1">
                    <div class="card-title text-slate-900 dark:text-white">Search User</div>
                </div>
            </header>
            <div class="card-text h-full space-y-4">
                @if ($errors->any())
                    <div class="py-[18px] px-6 font-normal text-sm rounded-md bg-danger-500 text-white">
                        <div class="flex items-center space-x-3 rtl:space-x-reverse">
                            <iconify-icon class="text-2xl flex-0" icon="system-uicons:target"></iconify-icon>
                            <p class="flex-1 font-Inter">
                                @foreach ($errors->all() as $error)
                                    {{ $error }} <br>
                                @endforeach
                            </p>
                            <div class="flex-0 text-xl cursor-pointer">
                                <iconify-icon icon="line-md:close"></iconify-icon>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="input-area">

                    <div class="relative">

                        <form action="{{ route('user.search') }}" method="GET">
                            <input type="text" class="form-control !pr-12" placeholder="Phone Number" name="phone_number"
                                id="phone_number" value="+972{{ old('phone_number') }}">
                            <button type="submit"
                                class="absolute right-0 top-1/2 -translate-y-1/2 w-9 h-full border-l border-l-slate-200 dark:border-l-slate-700 flex items-center justify-center">
                                <iconify-icon icon="heroicons-solid:search"></iconify-icon>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
