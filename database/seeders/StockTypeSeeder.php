<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockTypeSeeder extends Seeder
{
    static $stockTypes = [
        'FII',
        'ACAO',
        'OPCAO',
        'ADR',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$stockTypes as $type) {
            DB::table('stock_types')->insert([
                'name' => $type,
                'created_at' => Carbon::now()->utc('America/Sao_Paulo'),
                'updated_at' => Carbon::now()->utc('America/Sao_Paulo'),
                'deleted_at' => Carbon::now()->utc('America/Sao_Paulo'),
                'active' => true,
            ]);
        }
    }
}
