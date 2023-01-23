<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Card;
use App\Models\Column;

class ColumnCardTest extends TestCase
{
    /**
     * @test
     * @group column_card
     */
    public function it_can_list_all_cards_from_a_column()
    {
        $column = Column::factory()->create();
        Card::factory()->count(10)->create([
            'column_id' => $column->id,
        ]);
        $this->assertDatabaseHas('cards', [
            'column_id' => $column->id,
        ]);
        $response = $this->getJson("/api/v1/columns/{$column->id}/cards")
            ->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes' => [
                        'title',
                        'description',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ],
        ]);
    }

    /**
     * @test
     * @group column_card
     */
    public function it_can_list_update_relationship_between_colum_and_card()
    {
        $column = Column::factory()->create();
        $column2 = Column::factory()->create();
        $card = Card::factory()->create([
            'column_id' => $column->id,
        ]);
        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
            'column_id' => $column->id,
        ]);

        $this->patchJson("/api/v1/columns/{$column2->id}/relationships/cards", [
            'data' => [
                [
                    'id' => $card->id,
                    'type' => 'cards',
                ],
            ],
        ])->assertStatus(204);

        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
            'column_id' => $column2->id,
        ]);
    }
}
