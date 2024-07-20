<li class="@if (str_contains(Route::currentRouteName(), Str::lower($lable))) active @endif">
    <a href="#" class="navItem">
        <span class="flex items-center">
            <iconify-icon class=" nav-icon" icon="{{ $icon }}"></iconify-icon>
            <span>{{ $lable }}</span>
        </span>
        <iconify-icon class="icon-arrow" icon="heroicons-outline:chevron-right"></iconify-icon>
    </a>
    <ul class="sidebar-submenu">
        @foreach ($options as $option)
            @can($option['permission'])
                <li>
                    <a class="@if (Route::currentRouteName() === $option['routeName']) active @endif @if (Route::currentRouteName() === $option['routeEdit']) active @endif @if (Route::currentRouteName() === $option['routeDeleted']) active @endif"
                        href="{{ $option['route'] }}">{{ $option['lable'] }}</a>
                </li>
            @endcan
        @endforeach
    </ul>
</li>
