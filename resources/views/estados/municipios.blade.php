@extends('layouts.app', ['title' => 'Municipios', 'subtitle' => 'Consulta por estado'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Municipios</h3>
        <div class="text-muted small">
            <i class="bi bi-geo mr-1"></i>
            Estado: <span class="font-weight-bold">{{ $estado->nombre }}</span>
        </div>
    </div>

    <a href="{{ route('estados.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left mr-1"></i> Volver
    </a>
</div>

<div class="card shadow-soft">
    <div class="card-body">
        <table id="tabla-municipios" class="table table-hover table-striped table-bordered mb-0">
            <thead class="bg-white">
                <tr>
                    <th>Municipio</th>
                    <th style="width: 220px;">Clave</th>
                </tr>
            </thead>
            <tbody>
                @foreach($municipios as $m)
                    <tr>
                        <td class="font-weight-bold">{{ $m['nombre'] }}</td>
                        <td><span class="badge badge-light">{{ $m['clave'] }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#tabla-municipios').DataTable({
        pageLength: 10,
        order: [[0, 'asc']],
        language: {
            search: "Buscar:", lengthMenu: "Mostrar _MENU_", info: "Mostrando _START_ a _END_ de _TOTAL_", paginate: { previous: "Anterior", next: "Siguiente" }, zeroRecords: "Sin resultados",
        }
    });
</script>
@endsection