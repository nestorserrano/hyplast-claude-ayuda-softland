@if($menus->count() > 0)
    @foreach($menus as $menu)
        @if($menu->hijosActivos->count() > 0)
            {{-- Menú con submenús --}}
            <li class="nav-item has-treeview">
                <a href="#" class="nav-link">
                    @if($menu->icono)
                        <i class="{{ $menu->icono }} nav-icon"></i>
                    @else
                        <i class="fas fa-circle nav-icon"></i>
                    @endif
                    <p>
                        {{ $menu->nombre }}
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    @foreach($menu->hijosActivos as $hijo)
                        <li class="nav-item">
                            <a href="{{ $hijo->ruta ? (Str::startsWith($hijo->ruta, 'route:') ? route(Str::after($hijo->ruta, 'route:')) : url($hijo->ruta)) : '#' }}"
                               class="nav-link {{ request()->is(ltrim(parse_url($hijo->ruta, PHP_URL_PATH), '/')) ? 'active' : '' }}">
                                @if($hijo->icono)
                                    <i class="{{ $hijo->icono }} nav-icon"></i>
                                @else
                                    <i class="far fa-circle nav-icon"></i>
                                @endif
                                <p>{{ $hijo->nombre }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            {{-- Menú simple sin submenús --}}
            <li class="nav-item">
                <a href="{{ $menu->ruta ? (Str::startsWith($menu->ruta, 'route:') ? route(Str::after($menu->ruta, 'route:')) : url($menu->ruta)) : '#' }}"
                   class="nav-link {{ request()->is(ltrim(parse_url($menu->ruta, PHP_URL_PATH), '/')) ? 'active' : '' }}">
                    @if($menu->icono)
                        <i class="{{ $menu->icono }} nav-icon"></i>
                    @else
                        <i class="fas fa-circle nav-icon"></i>
                    @endif
                    <p>{{ $menu->nombre }}</p>
                </a>
            </li>
        @endif
    @endforeach
@else
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="fas fa-info-circle nav-icon"></i>
            <p>No hay menús asignados</p>
        </a>
    </li>
@endif
