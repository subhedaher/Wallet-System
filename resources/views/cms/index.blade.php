@extends('cms.parent')

@section('title', 'Home')
@section('main-title', 'Home')

@section('content')
    <div class="space-y-5">
        <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-4">
            @canany(['Create-Admin'])
                @include('cms.components.card', [
                    'icon' => 'eos-icons:admin',
                    'info' => 'Admins',
                    'total' => $admins,
                ])
                @include('cms.components.card', [
                    'icon' => 'bxs:category-alt',
                    'info' => 'Payment Categories',
                    'total' => $paymentCategories,
                ])
                @include('cms.components.card', [
                    'icon' => 'carbon:location-company-filled',
                    'info' => 'Payment Providers',
                    'total' => $paymentProviders,
                ])
                @include('cms.components.card', [
                    'icon' => 'map:moving-company',
                    'info' => 'Shipping Points',
                    'total' => $shippingPonints,
                ])
                @include('cms.components.card', [
                    'icon' => 'heroicons:user-group',
                    'info' => 'Users',
                    'total' => $users,
                ])
            @endcanany
        </div>
    </div>
@endsection
