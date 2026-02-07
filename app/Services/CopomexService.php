<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CopomexService
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.copomex.base_url');
        $this->token = config('services.copomex.token');
    }

    public function getEstados(): array
    {
        $resp = Http::timeout(20)->get("{$this->baseUrl}/get_estado_clave", ['token' => $this->token]);

        if (!$resp->ok()) {
            throw new \RuntimeException("COPOMEX HTTP {$resp->status()}");
        }

        return $resp->json();
    }

    public function getMunicipiosPorEstado(string $estado): array
    {
        $resp = Http::timeout(20)->get(
            "{$this->baseUrl}/get_municipio_clave_por_estado/" . urlencode($estado), ['token' => $this->token]
        );

        if (!$resp->ok()) {
            throw new \RuntimeException("COPOMEX HTTP {$resp->status()}");
        }

        return $resp->json();
    }
}