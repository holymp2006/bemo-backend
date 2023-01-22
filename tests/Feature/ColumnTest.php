<?php

namespace Tests\Feature;

use App\Models\Column;
use Tests\TestCase;

class ColumnTest extends TestCase
{
    /**
     * @test
     * @group column
     */
    public function it_can_list_all_columns()
    {
        Column::factory()->count(10)->create();

        $response = $this->getJson('/api/v1/columns')
            ->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes' => [
                        'title',
                        'created_at',
                        'updated_at',
                    ],
                    'relationships' => [
                        'cards' => [
                            'links' => [
                                'self',
                                'related',
                            ],
                        ],
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
     * @group column
     */
    public function it_can_create_a_column()
    {
        $response = $this->postJson('/api/v1/columns', [
            'data' => [
                'type' => 'columns',
                'attributes' => [
                    'title' => 'Test Column',
                ],
            ],
        ])->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes' => [
                    'title',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
        $this->assertDatabaseHas('columns', [
            'id' => $response->json('data.id'),
            'title' => $response->json('data.attributes.title'),
        ]);
    }
    /**
     * @test
     * @group column
     */
    public function it_can_delete_a_column_and_related_cards()
    {
        $column = Column::factory()->create();
        $card = $column->cards()->create([
            'title' => 'Test Card',
            'description' => 'Test Description',
        ]);
        $this->assertDatabaseHas('columns', [
            'id' => $column->id,
        ]);
        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
        ]);
        $this->deleteJson("/api/v1/columns/{$column->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('columns', [
            'id' => $column->id,
        ]);
        $this->assertDatabaseMissing('cards', [
            'id' => $card->id,
        ]);
    }
}
