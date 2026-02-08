<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class CopomexSetting extends Model
{
    protected $fillable = ['token_test', 'token_real', 'credits_real', 'credits_checked_at'];

    protected $casts = [
        'credits_checked_at' => 'datetime',
    ];

    protected $hidden = ['token_real'];

    public function setTokenRealAttribute($value)
    {
        $this->attributes['token_real'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getTokenRealAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function getConsultasDisponiblesReal(): ?int
    {
        $s = $this->settings();

        if (!$s->token_real) return null;

        $resp = Http::timeout(20)->get("{$this->baseUrl}/cuenta/consultas_disponibles", [
            'token' => $s->token_real
        ]);

        if (!$resp->ok()) return null;

        $json = $resp->json();
        $val = $json['response']['consultas_disponibles'] ?? null;

        return is_numeric($val) ? (int) $val : null;
    }
}
