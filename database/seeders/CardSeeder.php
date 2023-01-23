<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Column;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $column = Column::factory()->create();
        Card::factory()->count(3)->create([
            'column_id' => $column->id,
        ]);
    }
}
