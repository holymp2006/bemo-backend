<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Card;
use App\Models\Column;

class CardTest extends TestCase
{
    /**
     * @test
     * @group card
     */
    public function it_can_list_all_cards()
    {
        $column = Column::factory()->create();
        Card::factory()->count(10)->create([
            'column_id' => $column->id,
        ]);

        $response = $this->get('/api/v1/cards')
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
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'path',
                'per_page',
                'to',
                'total',
            ],
        ]);
    }
    /**
     * @test
     * @group card
     */
    public function it_can_create_a_card()
    {
        $column = Column::factory()->create();
        $response = $this->post('/api/v1/cards', [
            'data' => [
                'type' => 'cards',
                'attributes' => [
                    'title' => 'Test Card',
                    'description' => 'Test Description',
                    'column_id' => $column->id,
                ],
            ],
        ])->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes' => [
                    'title',
                    'description',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $this->assertDatabaseHas('cards', [
            'id' => $response->json('data.id'),
            'title' => 'Test Card',
            'description' => 'Test Description',
        ]);
        $column->refresh();
        $this->assertEquals(1, $column->cards->count());
    }
    /**
     * @test
     * @group card
     */
    public function it_can_soft_delete_a_card()
    {
        $column = Column::factory()->create();
        $card = Card::factory()->create([
            'column_id' => $column->id,
        ]);
        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
        ]);
        $this->delete("/api/v1/cards/{$card->id}")
            ->assertStatus(204);

        $this->assertSoftDeleted('cards', [
            'id' => $card->id,
        ]);
    }
}
