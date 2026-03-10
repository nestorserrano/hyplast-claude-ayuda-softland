@if(Auth::check() && isset($empresaActual))
<li class="nav-item">
    <a href="{{ route('select-conjunto') }}" class="nav-link">
        <i class="nav-icon fas fa-building text-primary"></i>
        <p>
            <span class="badge badge-success">{{ $empresaActual['codigo'] }}</span>
            @if($empresaActual['nombre'])
                {{ Str::limit($empresaActual['nombre'], 20) }}
            @else
                Empresa
            @endif
        </p>
    </a>
</li>
@endif
