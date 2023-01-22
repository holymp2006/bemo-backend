<?php

namespace Tests\Feature;

use Tests\TestCase;

class DumpDbTest extends TestCase
{
    /**
     * @test
     * @group dump_db
     */
    public function it_can_dump_the_database()
    {
        $response = $this->get('/dump-db');

        $response->assertStatus(200);
        $response->assertDownload();
    }
}
