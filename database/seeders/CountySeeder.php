<?php

namespace Database\Seeders;

use App\Models\County;
use App\Models\HealthRegion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = public_path('counties.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);

        $counties = County::all();

        foreach ($counties as $item) {
            $item->update([
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $health_regions = HealthRegion::all();

        foreach ($health_regions as $item) {
            $item->update([
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
