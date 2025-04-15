@php
    use App\Models\Setting;
    $themeColor = Setting::where('key', 'theme_color')->value('value') ?? '#3b82f6';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard</title>
    <link rel="icon" type="image/png" href="/assets/img/favicon-96x96.png" sizes="96x96" />
    <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/admin/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/list-vehicle.css') }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    <style>
        :root {
            --theme-color:
                {{ $themeColor }}
            ;
        }

        /* Buttons */
        .btn-theme {
            background-color: var(--theme-color);
            color: white;
        }

        .btn-theme:hover {
            filter: brightness(90%);
        }

        /* Text Links */
        a {
            color: var(--theme-color);
        }

        a:hover {
            text-decoration: underline;
        }

        /* Navbar, Sidebar, etc. */
        .bg-theme {
            background-color: var(--theme-color) !important;
            color: white;
        }

        /* Optional: cards or containers */
        .border-theme {
            border: 2px solid var(--theme-color);
        }

        .text-theme {
            color: var(--theme-color);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
        }

        .setting-sidebar {
            display: flex;
            width: 100%;
        }

        .sidebar {
            background-color: #fff;
            width: 280px;
            padding: 30px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.08);
            z-index: 10;
        }

        .sidebar-item {
            display: block;
            padding: 12px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            color: #555;
            text-decoration: none;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar-item.active {
            background-color: #ff8c00;
            color: #fff;
            font-weight: 500;
        }

        .sidebar-item:hover {
            background-color: #ffe0b2;
            color: #333;
        }

        .flex-grow-1 {
            flex-grow: 1;
            padding: 30px;
            background-color: #f4f4f4;
        }

        .fw-bold {
            color: #333;
            font-size: 2rem;
            margin-bottom: 25px;
        }

        .nav-tabs {
            border-bottom: 2px solid #ddd;
            margin-bottom: 30px;
        }

        .nav-tabs .nav-item .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #777;
            padding: 10px 20px;
            transition: color 0.3s ease, border-bottom 0.3s ease;
            border-radius: 6px 6px 0 0;
        }

        .nav-tabs .nav-item .nav-link.active {
            color: #ff8c00;
            border-bottom-color: #ff8c00;
            font-weight: 500;
            background-color: #fff;
        }

        .nav-tabs .nav-item .nav-link:hover {
            color: #333;
        }
    </style>

</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        @php

            $logoPath = Setting::where('key', 'site_logo')->value('value') ?? 'default-logo.png';
        @endphp

        <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="navbar-logo">

        <h1>Arkila</h1>
        <ul>
            <h2 class="centered-header">Overview</h2>
            <li><i class='bx bx-home'></i><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>

            <h2 class="centered-header">Car Rental Management</h2>
            @php
                $carRentalManagementLinks = [
                    ['url' => '/admin/bookings', 'icon' => 'bx bx-book', 'text' => 'Bookings'],
                    ['url' => '/admin/vehicles', 'icon' => 'bx bx-car', 'text' => 'Vehicles'],
                    ['url' => '/admin/gps', 'icon' => 'bx bx-map', 'text' => 'GPS Tracking']
                ];
            @endphp
            @foreach ($carRentalManagementLinks as $link)
                <li>
                    <i class="{{ $link['icon'] }}"></i>
                    <a href="{{ $link['url'] }}">{{ $link['text'] }}</a>
                </li>
            @endforeach

            <h2 class="centered-header">User Management</h2>
            <li><i class='bx bx-user'></i><a href="{{ route('admin.clients') }}">Clients</a></li>
            <li><i class='bx bx-user'></i><a href="/drivers">Drivers</a></li>
            <li><i class='bx bx-user'></i><a href="{{ route('admin.partners.index') }}">Partners</a></li>
            <li><i class='bx bx-user'></i><a href="{{ route('admin.accounts') }}">Accounts</a></li>

            <h2 class="centered-header">Others</h2>
            <li><i class='bx bx-cog'></i><a href="{{ route('admin.admin-settings') }}">Settings</a></li>
            <li><i class='bx bx-bar-chart'></i><a href="/reports">Reports</a></li>

            <li class="logout-item">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"><i class='bx bx-log-out'></i> Logout</button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <section class="content" style="flex-grow: 1;">
        <nav class="navbar">
            <div class="navbar-left">
                <h2>Dashboard</h2>
            </div>
            <div class="navbar-right">
                <div class="dropdown">
                    <button class="btn-theme"><i class='bx bx-chevron-down'></i></button>
                    <div class="dropdown-content">
                        <a href="/profile">Profile</a>
                        <a href="/settings">Settings</a>
                        <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-theme"
                                style="background: none; border: none; color: inherit; cursor: pointer; padding: 12px 16px; text-decoration: none; display: block; transition: background-color 0.3s;">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <section class="setting-sidebar">
            <div class="sidebar">
                <a href="{{ route('admin.admin-settings') }}"
                    class="sidebar-item {{ request()->routeIs('admin.admin-settings') ? 'active' : '' }}">
                    General Settings
                </a>

                <a href="{{ route('admin.companyManagement') }}"
                    class="sidebar-item {{ request()->routeIs('admin.companyManagement') ? 'active' : '' }}">
                    Company Management
                </a>

                <a href="{{ route('admin.feedbackReviews') }}"
                    class="sidebar-item {{ request()->is('admin/feedback-reviews') ? 'active' : '' }}">
                    Feedback and Reviews
                </a>

                <a href="#" class="sidebar-item {{ request()->is('admin/backup') ? 'active' : '' }}">
                    Backup and Restore
                </a>

                <a href="{{ route('admin.archives') }}"
                    class="sidebar-item {{ request()->is('admin/archives') ? 'active' : '' }}">
                    Archives
                </a>
            </div>

            @yield('content')
        </section>
    </section>
</body>

</html>