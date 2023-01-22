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

        $response = $this->get('/api/v1/columns')
            ->assertStatus(200);
        $response->dd();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'attributes' => [
                        'name',
                        'description',
                        'created_at',
                        'updated_at',
                    ],
                    'relationships' => [
                        'tasks' => [
                            'data' => [
                                '*' => [
                                    'id',
                                    'type',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'links' => [
                'self',
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
}
