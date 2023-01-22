<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Testing\TestResponse;

class HandlerTest extends TestCase
{
    /**
     * @test 
     * @group handler    
     */
    public function it_converts_an_exception_into_a_json_api_error_response()
    {
        $handler = app(Handler::class);
        $request = Request::create('/test', 'GET');
        $request->headers->set('accept', 'application/vnd.api+json');
        $exception = new \Exception('Test exception');
        $response = $handler->render($request, $exception);
        TestResponse::fromBaseResponse($response)->assertJson([
            'errors' => [
                [
                    'title' => 'Exception',
                    'details' => 'Test exception',
                ]
            ]
        ]);
    }
}
