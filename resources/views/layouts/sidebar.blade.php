<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset(config('adminlte.logo_img', 'images/ms-icon-150x150.png')) }}"
             alt="{{ config('adminlte.logo_img_alt', 'Hyplast Logo') }}"
             class="{{ config('adminlte.logo_img_class', 'brand-image img-circle elevation-3') }}">
        <span class="brand-text font-weight-light">{!! config('adminlte.logo', config('app.name')) !!}</span>
    </a>

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_accordion'))
                    data-accordion="true"
                @else
                    data-accordion="false"
                @endif
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                id="main-sidebar-menu">

                {{-- Empresa Actual --}}
                @include('partials.empresa-menu-item')

                {{-- DEBUG: Verificar autenticación --}}
                <!-- Auth::check() = {{ Auth::check() ? 'TRUE' : 'FALSE' }} -->

                {{-- Cargar menús dinámicos desde base de datos --}}
                @if(Auth::check())
                    @php
                        $menusUsuario = \App\Models\Menu::getMenusParaUsuario(Auth::id());
                    @endphp

                    <!-- Total menús cargados: {{ $menusUsuario->count() }} -->

                    @foreach($menusUsuario as $menu)
                        <!-- Renderizando menú: {{ $menu->text }} -->
                        @include('pages.admin.menu-item-recursive', ['menu' => $menu])
                    @endforeach
                @else
                    <!-- Usuario NO autenticado -->
                @endif
            </ul>
        </nav>
    </div>

</aside>
