<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class DumpDbTest extends TestCase
{
    /**
     * @test
     * @group dump_db
     */
    public function it_can_dump_the_database()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;
        $response = $this->get("/dump-db?access_token={$token}");

        $response->assertStatus(200);
        $response->assertDownload();
    }
}
