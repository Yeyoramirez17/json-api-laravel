<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidateJsonApiDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ValidateJsonApiDocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp() : void
    {
        parent::setUp();

        $this->withoutJsonApiDocumentFormatting ();

        Route::any('test_route', fn() => 'OK')
            ->middleware(ValidateJsonApiDocument::class);
    }
    /**
     * @test
     */
    public function only_accepts_valid_json_api_document(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertSuccessful();

        $this->patchJson('test_route', [
            'data' => [
                'id' => '1',
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertSuccessful();
    }
    /**
     * @test
     */
    public function data_is_required(): void
    {
        $this->postJson('test_route', [])
            ->assertJsonApiValidationError('data');

        $this->patchJson('test_route', [])
            ->assertJsonApiValidationError('data');
    }
    /**
     * @test
     */
    public function data_must_be_an_array(): void
    {
        $this->postJson('test_route', [
            'data' => 'string'
        ])->assertJsonApiValidationError('data');

        $this->patchJson('test_route', [
            'data' => 'string'
        ])->assertJsonApiValidationError('data');
    }
    /**
     * @test
     */
    public function data_type_is_required(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'attributes' => [
                    'name' => 'required',
                ]
            ]
        ])->assertJsonApiValidationError('data.type');

        $this->patchJson('test_route', [
            'data' => [
                'attributes' => [
                    'name' => 'required',
                ]
            ]
        ])->assertJsonApiValidationError('data.type');
    }
    /**
     * @test
     */
    public function data_type_must_be_a_string(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 1,
                'attributes' => ['name' => 'test']
            ]
        ])->assertJsonApiValidationError('data.type');

        $this->patchJson('test_route', [
            'data' => [
                'type' => 1,
                'attributes' => ['name' => 'test']
            ]
        ])->assertJsonApiValidationError('data.type');
    }
    /**
     * @test
     */
    public function data_attribute_is_required(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 'string'
            ]
        ])->assertJsonApiValidationError('data.attributes');

        $this->patchJson('test_route', [
            'data' => [
                'type' => 'string'
            ]
        ])->assertJsonApiValidationError('data.attributes');
    }
    /**
     * @test
     */
    public function data_attribute_must_be_an_array(): void
    {
        $this->postJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => 'string'
            ]
        ])->assertJsonApiValidationError('data.attributes');

        $this->patchJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => 'string'
            ]
        ])->assertJsonApiValidationError('data.attributes');
    }

    /**
     * @test
     */
    public function data_id_is_required(): void
    {
        $this->patchJson('test_route', [
            'data' => [
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertJsonApiValidationError('data.id');
    }
    /**
     * @test
     */
    public function data_id_must_be_a_string(): void
    {
        $this->patchJson('test_route', [
            'data' => [
                'id' => 1,
                'type' => 'string',
                'attributes' => [
                    'name' => 'test'
                ]
            ]
        ])->assertJsonApiValidationError('data.id');
    }
}
