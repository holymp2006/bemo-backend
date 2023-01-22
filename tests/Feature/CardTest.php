<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Support\Carbon;

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

        $response = $this->getJson('/api/v1/cards')
            ->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes' => [
                        'title',
                        'description',
                        'status',
                        'date',
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
        $response = $this->postJson('/api/v1/cards', [
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
                    'deleted_at'
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
        $this->deleteJson("/api/v1/cards/{$card->id}")
            ->assertStatus(204);

        $this->assertSoftDeleted('cards', [
            'id' => $card->id,
        ]);
    }
    /**
     * @test
     * @group card
     */
    public function it_can_update_a_card()
    {
        $column = Column::factory()->create();
        $card = Card::factory()->create([
            'column_id' => $column->id,
        ]);
        $this->assertDatabaseHas('cards', [
            'id' => $card->id,
            'title' => $card->title,
        ]);
        $response = $this->patchJson("/api/v1/cards/{$card->id}", [
            'data' => [
                'id' => $card->id,
                'type' => 'cards',
                'attributes' => [
                    'title' => 'Updated Test Card',
                    'description' => 'Updated Test Description',
                ],
            ],
        ])->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'type',
                'attributes' => [
                    'title',
                    'description',
                    'created_at',
                    'updated_at',
                    'deleted_at'
                ],
            ],
        ]);
        $this->assertDatabaseHas('cards', [
            'id' => $response->json('data.id'),
            'title' => 'Updated Test Card',
            'description' => 'Updated Test Description',
        ]);
    }
    /**
     * @test
     * @group card
     */
    public function it_can_filter_all_cards_by_date()
    {
        $column = Column::factory()->create();
        Card::factory()->count(10)->create([
            'column_id' => $column->id,
        ]);
        $card = Card::first();
        $card->date = Carbon::now()->subDays(2)
            ->format('Y-m-d');
        $card->save();

        $response = $this->getJson("/api/v1/cards?filter[date]={$card->date}")
            ->assertStatus(200);
        $this->assertEquals($card->id, $response->json('data.0.id'));
    }
    /**
     * @test
     * @group card
     */
    public function it_can_filter_all_cards_by_status()
    {
        $column = Column::factory()->create();
        Card::factory()->count(10)->create([
            'column_id' => $column->id,
        ]);
        $card = Card::first();
        $card->status = 0;
        $card->save();

        $response = $this->getJson("/api/v1/cards?filter[status]={$card->status}")
            ->assertStatus(200);
        $this->assertEquals($card->id, $response->json('data.0.id'));
    }
}
