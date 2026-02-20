<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SIPEBA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header/Nav */
        header {
            background-color: #003399;
            color: white;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        header p {
            font-size: 14px;
            opacity: 0.9;
        }

        nav {
            background-color: #005cc8;
            padding: 0;
            margin: 0;
        }

        nav ul {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
        }

        nav li {
            margin: 0;
        }

        nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            transition: background-color 0.3s;
        }

        nav a:hover {
            background-color: #003d99;
        }

        /* Main content */
        main {
            margin: 20px 0;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        /* Alert messages */
        .alert {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 4px;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }

        .alert-warning {
            background-color: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #003399;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s;
            margin-right: 5px;
        }

        .btn-primary {
            background-color: #003399;
            color: white;
        }

        .btn-primary:hover {
            background-color: #002966;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: black;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        /* Forms */
        form {
            max-width: 500px;
        }

        label {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
            font-weight: 500;
        }

        input, select, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #003399;
            box-shadow: 0 0 0 3px rgba(0,51,153,0.1);
        }

        .form-group {
            margin-bottom: 15px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            list-style: none;
            margin: 20px 0;
            gap: 5px;
        }

        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #003399;
        }

        .pagination a:hover {
            background-color: #f9f9f9;
        }

        .pagination .active span {
            background-color: #003399;
            color: white;
            border-color: #003399;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-pending {
            background-color: #ffc107;
            color: black;
        }

        .badge-approved {
            background-color: #28a745;
            color: white;
        }

        .badge-rejected {
            background-color: #dc3545;
            color: white;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #ddd;
            margin-top: 30px;
        }

        /* User info in header */
        .user-info {
            float: right;
            color: white;
            font-size: 14px;
        }

        .user-info a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }

        .user-info a:hover {
            text-decoration: underline;
        }

        /* Page header */
        .page-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #003399;
        }

        .page-header h2 {
            color: #003399;
            margin-bottom: 5px;
        }

        .page-header .breadcrumbs {
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <header>
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1>SIPEBA</h1>
                <p>Sistem Informasi Persediaan Barang</p>
            </div>
            @auth
            <div class="user-info">
                <span>{{ auth()->user()->name }}</span>
                <span>({{ auth()->user()->unitKerja?->nama_unit ?? 'Admin' }})</span>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            @endauth
        </div>
    </header>

    @auth
    <nav>
        <ul>
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('barang.index') }}">Master Barang</a></li>
            <li><a href="{{ route('penerimaan.index') }}">Penerimaan</a></li>
            <li><a href="{{ route('pengurangan.index') }}">Pengurangan</a></li>
            <li><a href="{{ route('laporan.index') }}">Laporan</a></li>
            @can('is-super-admin')
            <li><a href="{{ route('rekap-setda.index') }}">Rekap SETDA</a></li>
            @endcan
        </ul>
    </nav>
    @endauth

    <div class="container">
        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Terjadi Kesalahan:</strong>
            <ul style="margin-top: 10px;">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <main>
            @yield('content')
        </main>
    </div>

    <footer>
        <p>&copy; 2025 SIPEBA - Sekretariat Daerah Kabupaten Bantul. All rights reserved.</p>
    </footer>
</body>
</html>
