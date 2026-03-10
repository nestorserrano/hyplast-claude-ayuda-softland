{{-- MENÚS ESTÁTICOS ELIMINADOS - AHORA VIENEN DE LA BASE DE DATOS --}}
{{-- Los menús Home, Mensajes y Mi Perfil ahora se cargan dinámicamente desde la tabla 'menus' --}}
{{-- Si necesitas editarlos, usa /menus en el panel de administración --}}

{{--
<li class="nav-item">
    <a href="{{ url('/home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>Home</p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ url('/messages') }}" class="nav-link {{ Request::is('messages*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-envelope"></i>
        <p>
            Mensajes
            @php
                $unreadCount = 0;
                try {
                    if (auth()->check() && auth()->user()) {
                        $unreadCount = auth()->user()->unreadMessagesCount();
                    }
                } catch (\Exception $e) {
                    \Log::error('Error en unreadMessagesCount: ' . $e->getMessage());
                }
            @endphp
            @if($unreadCount > 0)
                <span class="badge badge-danger right">{{ $unreadCount }}</span>
            @endif
        </p>
    </a>
</li>

<li class="nav-item">
    <a href="{{ url('/profile/' . (auth()->check() ? auth()->id() : '')) }}" class="nav-link {{ auth()->check() && Request::is('profile/' . auth()->id()) ? 'active' : '' }}">
        <i class="nav-icon fas fa-user"></i>
        <p>Mi Perfil</p>
    </a>
</li>
--}}

{{-- MENÚS DINÁMICOS DESDE BASE DE DATOS --}}
@include('pages.admin.menu')
{{-- @include('pages.user.menu') COMENTADO: pages.admin.menu ya incluye todos los menús del usuario --}}
