<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CopomexSetting;

class CopomexSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        CopomexSetting::updateOrCreate(
            ['id' => 1],
            [
            'token_test' => 'pruebas',
            'token_real' => 'ffac40be-95a9-48a7-a4a5-f7e91bd581b3',
            'credits_real' => 40,
            'credits_checked_at' => now(),
            ]
        );
    }
}
