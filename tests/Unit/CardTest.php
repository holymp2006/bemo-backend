<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Card;
use App\Models\Column;

class CardTest extends TestCase
{
    /**
     * @test 
     * @group card    
     */
    public function it_soft_deletes()
    {
        $column = Column::factory()->create();
        $card = Card::factory()->create([
            'column_id' => $column->id,
        ]);
        $this->assertEquals(1, $card->fresh()->status);
        $card->delete();
        $this->assertSoftDeleted($card);
        $this->assertEquals(0, $card->fresh()->status);
    }
}
