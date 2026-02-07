<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Services\CopomexService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstadoController extends Controller
{
    public function index()
    {
        $estados = Estado::orderBy('nombre')->get();
        $ultimaSyncRaw = Estado::max('updated_at');
        $ultimaSync = $ultimaSyncRaw ? Carbon::parse($ultimaSyncRaw) : null;
        return view('estados.index', compact('estados', 'ultimaSync'));
    }

    public function sync(CopomexService $copomex)
    {
        $data = $copomex->getEstados();

        if (!empty($data['error'])) {
            return redirect()->route('estados.index')
                ->with('error', $data['message'] ?? 'COPOMEX respondió error.');
        }

        $map = $data['response']['estado_clave'] ?? null;

        if (!is_array($map)) {
            return redirect()->route('estados.index')
                ->with('error', 'Respuesta inesperada de COPOMEX.');
        }

        try {
            Estado::truncate();

            $rows = [];
            foreach ($map as $nombre => $clave) {
                $rows[] = [
                    'nombre' => (string) $nombre, 'clave' => (string) $clave,'created_at' => now(),'updated_at' => now(),
                ];
            }

            Estado::insert($rows);

            return redirect()->route('estados.index')->with('success', 'Estados sincronizados.');
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('estados.index')
                ->with('error', 'Error al sincronizar: ' . $e->getMessage());
        }
    }

    public function municipios(Estado $estado, CopomexService $copomex)
    {
        $data = $copomex->getMunicipiosPorEstado($estado->nombre);
        $map = $data['response']['municipio_clave'] ?? null;

        if (!is_array($map)) {
            return back()->with('error', 'Respuesta inesperada de COPOMEX (municipios).');
        }
        $municipios = [];

        foreach ($map as $nombre => $clave) {
            $municipios[] = [
                'nombre' => $nombre,'clave' => (string) $clave,
            ];
        }
        return view('estados.municipios', compact('estado', 'municipios'));
    }
}