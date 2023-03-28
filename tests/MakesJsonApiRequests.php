<?php
namespace tests;

use Closure;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;

trait MakesJsonApiRequests
{
    protected function setUp() : void
    {
        parent::setUp();

        TestResponse::macro(
            'assertJsonApiValidationError',
            $this->assertJsonApiValidationError()
        );

    }
    protected function assertJsonApiValidationError() : Closure
    {
        return function($attribute) {
                /** @var TestResponse $this */

                try {
                    $this->assertJsonFragment([
                        'source' => ['pointer' => "/data/attributes/{$attribute}"]
                    ]);
                } catch (ExpectationFailedException $e)
                {
                    PHPUnit::fail("Failed to find a JSON:API validation error for key: '{$attribute}'"
                        . PHP_EOL . PHP_EOL .
                        $e->getMessage());
                }

                try {
                    $this->assertJsonStructure([
                        'errors' => [
                            ['title', 'detail', 'source' => ['pointer']]
                        ]
                    ]);
                } catch (ExpectationFailedException $e)
                {
                    PHPUnit::fail("Failed to find a valid JSON:API error response"
                        . PHP_EOL . PHP_EOL .
                        $e->getMessage());
                }

                $this->assertHeader(
                    'content-type', 'application/vnd.api+json'
                )->assertStatus(422);
        };
    }
    public function json($method, $uri, array $data = [], array $headers = [], $options = 0) : TestResponse
    {
        $headers['accept'] = 'application/vnd.api+json';

        return parent::json($method, $uri, $data, $headers, $options);
    }
    public function postJson($uri, array $data = [], array $headers = [], $options = 0) : TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::postJson($uri, $data, $headers, $options);
    }
    public function patchJson($uri, array $data = [], array $headers = [], $options = 0) : TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::patchJson($uri, $data, $headers, $options);
    }
    public function deleteJson($uri, array $data = [], array $headers = [], $options = 0)
    {
        $headers['content-type'] = 'application/vnd.api+json';

        return parent::deleteJson($uri, $data, $headers, $options);
    }
}
