@extends('layouts.app', ['title' => 'Estados', 'subtitle' => 'Catálogo base desde COPOMEX'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h3 class="mb-1">Estados</h3>
        <div class="text-muted small">
            <i class="bi bi-info-circle mr-1"></i>
            Datos del entorno de pruebas de COPOMEX.
        </div>
    </div>

    <form id="sync-form" method="POST" action="{{ route('estados.sync') }}" class="text-right">
        @csrf
        <button id="sync-btn" class="btn btn-success">
            <i class="bi bi-arrow-repeat mr-1"></i>
            Sincronizar con COPOMEX
        </button>
        @if($ultimaSync)
            <div class="text-muted small mb-2">
                <i class="bi bi-clock-history mr-1"></i>
                Última sincronización:
                <strong>{{ $ultimaSync->diffForHumans() }}</strong>
                <span class="text-muted">({{ $ultimaSync->format('d/m/Y H:i') }})</span>
            </div>
        @endif
    </form>
</div>

<div class="card shadow-soft">
    <div class="card-body">
        <table id="tabla-estados" class="table table-hover table-striped table-bordered mb-0">
            <thead class="bg-white">
                <tr>
                    <th>Nombre</th>
                    <th style="width: 220px;">Clave</th>
                    <th style="width: 160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estados as $estado)
                    <tr>
                        <td class="font-weight-bold">{{ $estado->nombre }}</td>
                        <td><span class="badge badge-light">{{ $estado->clave }}</span></td>
                        <td>
                            <a href="{{ route('estados.municipios', $estado->id) }}"
                               class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-building mr-1"></i> Municipios
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
  $('#tabla-estados').DataTable({
    pageLength: 10,
    order: [[0, 'asc']],
    language: {
      search: "Buscar:", lengthMenu: "Mostrar _MENU_", info: "Mostrando _START_ a _END_ de _TOTAL_", paginate: { previous: "Anterior", next: "Siguiente" }, zeroRecords: "Sin resultados",
    }
  });

  $('#sync-form').on('submit', function () {
    const btn = $('#sync-btn');
    btn.prop('disabled', true);
    btn.html('<span class="spinner-border spinner-border-sm mr-2"></span>Sincronizando...');
  });

  $(function () {
    const $alerts = $('.alert');
    if ($alerts.length) {
      setTimeout(() => $alerts.fadeOut(250, function(){ $(this).remove(); }), 2500);
    }
  });
</script>
@endsection