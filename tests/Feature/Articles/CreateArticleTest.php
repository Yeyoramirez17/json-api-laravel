<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Routing\Route;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_create_article(): void
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo Articulo',
                    'slug' =>'nuevo-articulo',
                    'content' => 'Contenido del articulo'
                ]
            ]
        ]);

        $response->assertCreated();

        $article = Article::first();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );

        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Nuevo Articulo',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido del articulo',
                ],
                'links' => [
                    'self' => route('api.v1.articles.show', $article)
                ]
            ]
        ]);
    }
    /**
     * @test
     */
    public function title_is_required(): void
    {
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'slug' =>'nuevo-articulo',
                    'content' => 'Contenido del articulo'
                ]
            ]
        ]);

        $response->assertJsonApiValidationError('title');
    }
    /**
     * @test
     */
    public function title_must_be_at_least_4_characters(): void
    {
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nue',
                    'slug' =>'nuevo-articulo',
                    'content' => 'Contenido del articulo'
                ]
            ]
        ]);

        $response->assertJsonApiValidationError('title');
    }
    /**
     * @test
     */
    public function slug_is_required(): void
    {
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo Articulo',
                    'content' => 'Contenido del articulo'
                ]
            ]
        ]);

        $response->assertJsonApiValidationError('slug');
    }
    /**
     * @test
     */
    public function content_is_required(): void
    {
        $response = $this->postJson(route('api.v1.articles.create'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo Articulo',
                    'slug' =>'nuevo-articulo'
                ]
            ]
        ]);
        $response->assertJsonApiValidationError('content');
    }
}
