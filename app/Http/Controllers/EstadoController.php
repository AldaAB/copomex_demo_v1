<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Models\CopomexSetting;
use App\Services\CopomexService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    public function index()
    {
        $estados = Estado::orderBy('nombre')->get();
        $ultimaSyncRaw = Estado::max('updated_at');
        $ultimaSync = $ultimaSyncRaw ? Carbon::parse($ultimaSyncRaw) : null;

        return view('estados.index', compact('estados', 'ultimaSync'));
    }

    public function sync(Request $request, CopomexService $copomex)
    {
        $mode = $request->input('mode', 'test');
        $mode = in_array($mode, ['test', 'real'], true) ? $mode : 'test';

        $settings = CopomexSetting::firstOrCreate(['id' => 1], [
            'token_test' => 'pruebas',
        ]);
        if ($mode === 'real') {
            $needsRefresh = $settings->credits_real === null
                || $settings->credits_checked_at === null
                || $settings->credits_checked_at->lt(now()->subHours(24));

            if ($needsRefresh) {
                try {
                    $fresh = $copomex->getConsultasDisponiblesReal();
                    if ($fresh !== null) {
                        $settings->credits_real = $fresh;
                        $settings->credits_checked_at = now();
                        $settings->save();
                    }
                } catch (\Throwable $e) {
                    report($e);
                }
            }
        }
        try {
            $data = $copomex->getEstados($mode);
        } catch (\Throwable $e) {
            return redirect()->route('estados.index')
                ->with('mode_used', $mode)
                ->with('credits_left', $settings->credits_real)
                ->with('error', $e->getMessage());
        }

        if (($data['error'] ?? false) === true) {
            $code = (int)($data['code_error'] ?? 0);

            if ($mode === 'real' && $code === 5) {
                $settings->credits_real = 0;
                $settings->credits_checked_at = now();
                $settings->save();
            }

            return redirect()->route('estados.index')
                ->with('mode_used', $mode)
                ->with('credits_left', $settings->credits_real)
                ->with('error', $data['error_message'] ?? 'COPOMEX respondió error.');
        }

        $map = $data['response']['estado_clave'] ?? null;
        if (!is_array($map)) {
            return redirect()->route('estados.index')
                ->with('mode_used', $mode)
                ->with('credits_left', $settings->credits_real)
                ->with('error', 'Respuesta inesperada de COPOMEX.');
        }

        try {
            Estado::truncate();

            $rows = [];
            foreach ($map as $nombre => $clave) {
                $rows[] = [
                    'nombre' => (string) $nombre,
                    'clave' => (string) $clave,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Estado::insert($rows);

            $creditsLeft = null;
            if ($mode === 'real' && $settings->credits_real !== null) {
                $settings->credits_real = max(0, $settings->credits_real - 1);
                $settings->save();
                $creditsLeft = $settings->credits_real;
            }

            return redirect()->route('estados.index')
                ->with('mode_used', $mode)
                ->with('credits_left', $creditsLeft)
                ->with('success', 'Estados sincronizados (' . ($mode === 'real' ? 'Reales' : 'Pruebas') . ').');

        } catch (\Throwable $e) {
            report($e);

            return redirect()->route('estados.index')
                ->with('mode_used', $mode)
                ->with('credits_left', $settings->credits_real)
                ->with('error', 'Error al sincronizar: ' . $e->getMessage());
        }
    }

    public function municipios(Request $request, Estado $estado, CopomexService $copomex)
    {
        $mode = $request->input('mode', 'test');
        $mode = in_array($mode, ['test', 'real'], true) ? $mode : 'test';

        $settings = CopomexSetting::firstOrCreate(['id' => 1], [
            'token_test' => 'pruebas',
        ]);

        try {
            $data = $copomex->getMunicipiosPorEstado($estado->nombre, $mode);
        } catch (\Throwable $e) {
            return back()
                ->with('mode_used', $mode)
                ->with('credits_left', $settings->credits_real)
                ->with('error', $e->getMessage());
        }

        if (($data['error'] ?? false) === true) {
            return back()
                ->with('mode_used', $mode)
                ->with('credits_left', $settings->credits_real)
                ->with('error', $data['error_message'] ?? 'Error consultando municipios.');
        }

        $map = $data['response']['municipio_clave'] ?? null;
        if (!is_array($map)) {
            return back()
                ->with('mode_used', $mode)
                ->with('credits_left', $settings->credits_real)
                ->with('error', 'Respuesta inesperada de COPOMEX (municipios).');
        }

        $municipios = [];
        foreach ($map as $nombre => $clave) {
            $municipios[] = [
                'nombre' => (string) $nombre,
                'clave' => (string) $clave,
            ];
        }
        if ($mode === 'real' && $settings->credits_real !== null) {
            $settings->credits_real = max(0, $settings->credits_real - 1);
            $settings->save();
        }

        return view('estados.municipios', compact('estado', 'municipios'));
    }
}