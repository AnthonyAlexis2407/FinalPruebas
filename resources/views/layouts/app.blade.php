<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BobaCat</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@700&display=swap"
        rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            /* Elegant Professional Palette */
            --primary-color: #f43f5e;
            /* Vibrant Rose for accents */
            --primary-hover: #e11d48;

            --sidebar-bg: #0f172a;
            /* Deep Navy from reference */
            --sidebar-text: #94a3b8;
            --sidebar-active-bg: rgba(244, 63, 94, 0.15);
            --sidebar-active-text: #ffffff;

            --bg-body: #f8fafc;
            /* Clean Light Gray */
            --card-border: rgba(226, 232, 240, 0.8);
            --text-dark: #1e293b;
            --text-muted: #64748b;

            --sidebar-width: 260px;
            --header-height: 70px;
            --border-radius: 4px;
            /* Straight, professional corners */
            --card-radius: 0px;
            /* Corporate sharp look */

            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar Styles - Modern Dark */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            z-index: 1050;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .sidebar-brand {
            font-weight: 700;
            color: white;
            font-size: 1.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand i {
            color: var(--primary-color);
            font-size: 1.25rem;
        }

        .sidebar-content {
            padding: 1.5rem 1rem;
            flex: 1;
            overflow-y: auto;
        }

        .nav-section-title {
            font-size: 0.7rem;
            font-weight: 600;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 1.5rem 0 0.75rem 0.5rem;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.75rem 1rem;
            color: var(--sidebar-text) !important;
            border-radius: var(--border-radius);
            font-weight: 500;
            margin-bottom: 0.25rem;
            transition: all 0.2s;
            font-size: 0.95rem;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            opacity: 0.7;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white !important;
        }

        .sidebar .nav-link.active {
            background: var(--primary-color);
            color: white !important;
            box-shadow: 0 4px 12px rgba(244, 63, 94, 0.3);
        }

        .sidebar .nav-link.active i {
            opacity: 1;
        }

        /* Main Wrapper */
        .main-wrapper {
            flex: 1;
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .content-container {
            padding: 2rem;
            flex: 1;
        }

        /* UI Elements Refinement */
        .card {
            border: 1px solid var(--card-border);
            border-radius: var(--card-radius);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(244, 63, 94, 0.2);
        }

        .user-dropdown-btn {
            background: #f1f5f9;
            border: none;
            padding: 5px 12px 5px 5px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .avatar-circle {
            width: 35px;
            height: 35px;
            background: #e2e8f0;
            color: var(--text-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .category-pill {
            background: white;
            border: 1px solid #e2e8f0;
            color: var(--text-muted);
            padding: 0.5rem 1.25rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .category-pill.active {
            background: #f1f5f9;
            color: var(--text-dark);
            border-color: #cbd5e1;
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    @auth
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                @if (isset($activeStore))
                    @if (Auth::user()->isAdmin())
                        <div class="dropdown w-100">
                            <a href="#"
                                class="sidebar-brand text-decoration-none dropdown-toggle d-flex align-items-center gap-2"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-shop"></i>
                                <span class="text-truncate">{{ $activeStore->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark border-0 shadow-lg w-100 mt-2">
                                <li class="dropdown-header text-uppercase small opacity-50">Cambiar Tienda</li>
                                @foreach ($availableStores as $store)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center justify-content-between {{ $activeStore->id == $store->id ? 'active' : '' }}"
                                            href="{{ route('stores.switch', $store) }}">
                                            <span>{{ $store->name }}</span>
                                            @if ($activeStore->id == $store->id)
                                                <i class="bi bi-check2"></i>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                                <li>
                                    <hr class="dropdown-divider opacity-25">
                                </li>
                                <li>
                                    <a class="dropdown-item small" href="#">
                                        <i class="bi bi-plus-circle me-2"></i> Nueva Tienda
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="sidebar-brand text-decoration-none d-flex align-items-center gap-2">
                            <i class="bi bi-shop"></i>
                            <span class="text-truncate">{{ $activeStore->name }}</span>
                        </div>
                    @endif
                @else
                    <a href="{{ route('products.index') }}" class="sidebar-brand text-decoration-none">
                        <i class="bi bi-shop"></i> BobaCat
                    </a>
                @endif
            </div>

            <div class="sidebar-content">
                <div class="nav-section">
                    <div class="nav-section-title">Operaciones</div>
                    <a href="{{ route('sales.pos') }}"
                        class="nav-link {{ request()->routeIs('sales.pos') ? 'active' : '' }}">
                        <i class="bi bi-calculator-fill"></i> Ventas (POS)
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Logística</div>
                    <a href="{{ route('products.index') }}"
                        class="nav-link {{ request()->routeIs('products.*') || request()->routeIs('inventory.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam-fill"></i> Inventario
                    </a>
                    <a href="{{ route('categories.index') }}"
                        class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tags-fill"></i> Categorías
                    </a>
                </div>

                <div class="nav-section">
                    <div class="nav-section-title">Reportes</div>
                    <a href="{{ route('reports.daily') }}"
                        class="nav-link {{ request()->routeIs('reports.daily') ? 'active' : '' }}">
                        <i class="bi bi-graph-up-arrow"></i> Reporte Diario
                    </a>
                    <a href="{{ route('reports.monthly') }}"
                        class="nav-link {{ request()->routeIs('reports.monthly') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check-fill"></i> Reporte Mensual
                    </a>
                </div>

                <div class="nav-section mt-auto">
                    <div class="nav-section-title">Sistema</div>
                    <a href="{{ route('users.index') }}"
                        class="nav-link {{ request()->routeIs('users.index') || request()->routeIs('register') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i> Usuarios
                    </a>
                </div>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <header class="top-navbar">
                <div class="d-flex align-items-center w-100">
                    <button class="btn btn-light d-lg-none me-3" id="sidebarToggle">
                        <i class="bi bi-list fs-4"></i>
                    </button>

                    <h5 class="mb-0 fw-bold d-none d-md-block">
                        @yield('page_title', 'Panel de Control')
                    </h5>

                    <div class="ms-auto dropdown">
                        <a href="#" class="user-dropdown-btn text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <div class="avatar-circle">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="d-none d-sm-block text-start">
                                <div class="fw-bold text-dark small" style="line-height: 1.2;">{{ Auth::user()->name }}
                                </div>
                                <div class="text-muted d-flex align-items-center gap-1" style="font-size: 0.7rem;">
                                    <span>{{ Auth::user()->isAdmin() ? 'Administrador' : 'Cajero' }}</span>
                                    @if (isset($activeStore))
                                        <span class="opacity-50">•</span>
                                        <span class="text-primary fw-medium">{{ $activeStore->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2 mt-2">
                            <li><a class="dropdown-item py-2 px-3 rounded-3" href="{{ route('users.index') }}"><i
                                        class="bi bi-person me-2"></i> Perfil</a></li>
                            <li>
                                <hr class="dropdown-divider opacity-50">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item py-2 px-3 rounded-3 text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="content-container">
                @if (session('success'))
                    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    @else
        <!-- For Auth routes (Login) -->
        <div class="w-100">
            @yield('content')
        </div>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sidebarToggle').click(function() {
                $('#sidebar').toggleClass('show');
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
