<aside class="admin-sidebar">
    <div class="admin-sidebar__title">
        Panel Admin
    </div>

    <nav class="admin-sidebar__nav">
        <ul>

            {{-- Dashboard --}}
            <li>
                <a href="{{ url('admin') }}" class="admin-sidebar__link {{ request()->is('admin') ? 'active' : '' }}">
                    🏠 Dashboard
                </a>
            </li>

            {{-- Estudiantes --}}
            <li>
                <a href="{{ url('admin/estudiantes') }}"
                    class="admin-sidebar__link {{ request()->is('admin/estudiantes*') ? 'active' : '' }}">
                    🎓 Estudiantes
                </a>
            </li>
            {{-- Administradores --}}
            <li>
                <a href="{{ url('admin/administradores') }}"
                    class="admin-sidebar__link {{ request()->is('admin/administradores*') ? 'active' : '' }}">
                    🛡️ Administradores
                </a>
            </li>

            {{-- Empresas --}}
            <li>
                <a href="{{ url('admin/empresas') }}"
                    class="admin-sidebar__link {{ request()->is('admin/empresas*') ? 'active' : '' }}">
                    🏢 Empresas
                </a>
            </li>

            {{-- Ofertas --}}
            <li>
                <a href="{{ url('admin/ofertas') }}"
                    class="admin-sidebar__link {{ request()->is('admin/ofertas*') ? 'active' : '' }}">
                    📦 Ofertas
                </a>
            </li>

            {{-- Postulantes --}}
            <li>
                <a href="{{ url('admin/postulantes') }}"
                    class="admin-sidebar__link {{ request()->is('admin/postulantes*') ? 'active' : '' }}">
                    👥 Postulantes
                </a>
            </li>

            {{-- Postulaciones --}}
            <li>
                <a href="{{ url('admin/postulaciones') }}"
                    class="admin-sidebar__link {{ request()->is('admin/postulaciones*') ? 'active' : '' }}">
                    📝 Postulaciones
                </a>
            </li>
            {{-- Recursos de Empleabilidad --}}
            <li>
                <a href="{{ url('admin/recursos') }}"
                    class="admin-sidebar__link {{ request()->is('admin/recursos*') ? 'active' : '' }}">
                    📚 Recursos
                </a>
            </li>
            {{-- Reportes --}}
            <li>
                <a href="{{ url('admin/reportes') }}"
                    class="admin-sidebar__link {{ request()->is('admin/reportes*') ? 'active' : '' }}">
                    📊 Reportes
                </a>
            </li>

        </ul>
    </nav>
</aside>
