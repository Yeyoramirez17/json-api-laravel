<?php

namespace Tests\Unit\JsonApi;

use App\JsonApi\Document;
use Mockery;
use PHPUnit\Framework\TestCase;
// use Tests\TestCase;

class DocumentTest extends TestCase
{
    /**
     * @test
     */
    public function can_create_json_api_documents(): void
    {
        $category = Mockery::mock('Category', function($mock) {
            $mock->shouldReceive('getResourceType')->andReturn('categories');
            $mock->shouldReceive('getRouteKey')->andReturn('category-id');
        });

        $document = Document::type('articles')
            ->id('article-id')
            ->attributes([
                'title' => 'Article Title',
            ])->relationshipsData([
                'category' => $category
            ])->toArray();

        // dump($document);
        $expected = [
            'data' => [
                'type' => 'articles',
                'id' => 'article-id',
                'attributes' => [
                    'title' => 'Article Title',
                ],
                'relationships' => [
                    'category' => [
                        'data' => [
                            'type' => 'categories',
                            'id' => $category->getRouteKey(),
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($expected, $document);
    }
}
