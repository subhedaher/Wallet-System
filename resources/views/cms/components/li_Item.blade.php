<li class="">
    <a href="{{$route}}" class="navItem @if(Route::currentRouteName() === $routeName) active @endif">
      <span class="flex items-center">
    <iconify-icon class=" nav-icon" icon="{{$icon}}"></iconify-icon>
    <span>{{$label}}</span>
      </span>
    </a>
</li>
