<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MsCategory;

class MsCategorySeeder extends Seeder
{
    public function run()
    {
        MsCategory::firstOrCreate(['name' => 'Income']);
        MsCategory::firstOrCreate(['name' => 'Expense']);
    }
}
