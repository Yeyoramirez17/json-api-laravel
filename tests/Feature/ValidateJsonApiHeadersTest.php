<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateJsonApiHeaders;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidateJsonApiHeadersTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();
        Route::any('test_route', fn() => 'OK')
            ->middleware(ValidateJsonApiHeaders::class);
    }
    /**
     * @test
     */
    public function accept_header_must_be_present_in_all_requests(): void
    {
        $this->get('test_route')->assertStatus(406);

        $this->get('test_route', [
            'accept' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }
    /**
     * @test
     */
    public function content_type_header_must_be_present_on_all_post_requests(): void
    {
        $this->post('test_route', [], [
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(415);

        $this->post('test_route', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }
    /**
     * @test
     */
    public function content_type_header_must_be_present_on_all_patch_requests(): void
    {
        $this->patch('test_route', [], [
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(415);

        $this->patch('test_route', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }
    /**
     * @test
     */
    public function content_type_header_must_be_present_in_responses(): void
    {
        $this->getJson('test_route', [
            'accept' => 'application/vnd.api+json'
        ])->assertHeader('content-type', 'application/vnd.api+json');

        $this->postJson('test_route', [], [
            'accept' => 'application/vnd.api+json',
            'content-Type', 'application/vnd.api+json'
        ])->assertHeader('content-type', 'application/vnd.api+json');

        $this->patchJson('test_route', [], [
            'accept' => 'application/vnd.api+json',
            'content-Type', 'application/vnd.api+json'
        ])->assertHeader('content-type', 'application/vnd.api+json');
    }
    /**
     * @test
     */
    public function content_type_header_must_not_be_present_in_empty_responses()
    {
        $this->withoutExceptionHandling();

        Route::any('empy_response', fn() => response()->noContent())
            ->middleware(ValidateJsonApiHeaders::class);

        $this->getJson('empy_response', [
            'accept' => 'application/vnd.api+json',
        ])->assertHeaderMissing('content-type');

        $this->postJson('empy_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type', 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->patchJson('empy_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type', 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');

        $this->deleteJson('empy_response', [], [
            'accept' => 'application/vnd.api+json',
            'content-type', 'application/vnd.api+json'
        ])->assertHeaderMissing('content-type');
    }
}
