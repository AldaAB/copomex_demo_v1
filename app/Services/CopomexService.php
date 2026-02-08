<?php

namespace App\Services;

use App\Models\CopomexSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CopomexService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            config('services.copomex.base_url', 'https://api.copomex.com/query'),
            '/'
        );
    }

    private function settings(): CopomexSetting
    {
        return CopomexSetting::firstOrCreate(
            ['id' => 1],
            ['token_test' => 'pruebas', 'token_real' => null]
        );
    }

    private function token(string $mode): string
    {
        $s = $this->settings();

        if ($mode === 'real') {
            if (!$s->token_real) {
                throw new \RuntimeException(
                    'No hay token real configurado en copomex_settings.'
                );
            }
            return $s->token_real;
        }

        return $s->token_test ?: 'pruebas';
    }

    public function getEstados(string $mode = 'test'): array
    {
        $token = $this->token($mode);

        $resp = Http::timeout(20)->get("{$this->baseUrl}/get_estado_clave", [
            'token' => $token
        ]);

        if (!$resp->ok()) {
            return ['error' => true, 'message' => "COPOMEX HTTP {$resp->status()}"];
        }

        return $resp->json();
    }

    public function getMunicipiosPorEstado(string $estado, string $mode = 'test'): array
    {
        $token = $this->token($mode);

        $resp = Http::timeout(20)->get(
            "{$this->baseUrl}/get_municipio_clave_por_estado/" . urlencode($estado),
            ['token' => $token]
        );

        if (!$resp->ok()) {
            return ['error' => true, 'message' => "COPOMEX HTTP {$resp->status()}"];
        }

        return $resp->json();
    }

    public function getConsultasDisponiblesReal(): ?int
    {
        $s = $this->settings();

        if (!$s->token_real) {
            return null;
        }

        $resp = Http::timeout(20)->get(
            "{$this->baseUrl}/cuenta/consultas_disponibles",
            ['token' => $s->token_real]
        );

        if (!$resp->ok()) {
            Log::warning('COPOMEX consultas_disponibles falló', [
                'status' => $resp->status()
            ]);
            return null;
        }

        $json = $resp->json();

        $val = $json['response']['consultas_disponibles'] ?? null;

        return is_numeric($val) ? (int) $val : null;
    }
}