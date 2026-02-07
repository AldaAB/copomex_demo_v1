<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'COPOMEX Demo' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        body { background: #cdcdcd; }
        .navbar { box-shadow: 0 6px 18px rgba(0,0,0,.08); }
        .card { border: 0; border-radius: 14px; }
        .card.shadow-soft { box-shadow: 0 10px 24px rgba(31,45,61,.08); }
        .btn { border-radius: 10px; }
        .badge-soft {
            background: rgba(13,110,253,.10);
            color: #0d6efd;
            border: 1px solid rgba(13,110,253,.15);
        }
        .table thead th { border-top: 0; }
        .dt-info { color: #6c757d; }
        .page-link { border-radius: 8px !important; margin: 0 2px; }
        .toolbar { gap: 10px; }

        .table.table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(13, 110, 253, .04);
        }

        .table.table-hover tbody tr:hover {
            background-color: rgba(116, 254, 121, 0.14);
        }
        
        .table.table-bordered td,
        .table.table-bordered th {
            border-color: rgba(0,0,0,.06);
        }
        .table.table-striped.table-hover tbody tr:hover {
            background-color:  rgba(116, 254, 121, 0.08);
        }
        .dataTables_wrapper .pagination .page-item.active .page-link {
        background-color: #198754; 
        border-color: #198754;
        color: #fff;
        }

        .dataTables_wrapper .pagination .page-link {
        color: #198754;
        }

        .dataTables_wrapper .pagination .page-link:hover {
        color: #fff;
        background-color: #157347;
        border-color: #157347;
        }

        .dataTables_wrapper .pagination .page-link:focus {
        box-shadow: 0 0 0 .2rem rgba(25,135,84,.25);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="bi bi-geo-alt-fill mr-2"></i>
            <span class="navbar-brand mb-0 h1">COPOMEX Demo</span>
        </div>
        <div class="text-white-50 small">
            {{ $subtitle ?? 'Estados y municipios' }}
        </div>
    </div>
</nav>

<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">
            <i class="bi bi-check-circle-fill mr-1"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0">
            <i class="bi bi-x-circle-fill mr-1"></i> {{ session('error') }}
        </div>
    @endif

    @yield('content')
</div>

<footer class="py-4">
    <div class="container text-center text-muted small">
        COPOMEX Demo · {{ date('Y') }}
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>

@yield('scripts')
</body>
</html>