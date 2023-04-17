<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_create_article(): void
    {
        $user = User::factory()->create();

        $category = Category::factory()->create();

        Sanctum::actingAs($user, ['article:create']);

        $response = $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'slug' => 'nuevo-articulo',
            'content' => 'Contenido del articulo',
            '_relationships' => [
                'category' => $category,
                'author' => $user,
            ]
        ])->assertCreated();

        $article = Article::first();

        $response->assertJsonApiResource($article, [
            'title' => 'Nuevo Articulo',
            'slug' => 'nuevo-articulo',
            'content' => 'Contenido del articulo',
        ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'Nuevo Articulo',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);
    }
    /**
     * @test
     */
    public function guests_cannot_create_article(): void
    {
        $response = $this->postJson(route('api.v1.articles.store'));

        $response->assertJsonApiError(
            title: 'Unauthenticated',
            detail: 'This action requires authentication.',
            status: '401'
        );

        $this->assertDatabaseCount('articles', 0);
    }
    /**
     * @test
     */
    public function title_is_required(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.articles.store'), [
            'slug' => 'nuevo-articulo',
            'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationError('title');
    }
    /**
     * @test
     */
    public function title_must_be_at_least_4_characters(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nue',
            'slug' => 'nuevo-articulo',
            'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationError('title');
    }
    /**
     * @test
     */
    public function slug_is_required(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationError('slug');
    }
    /**
     * @test
     */
    public function slug_must_be_unique(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $article = Article::factory()->create();

        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'slug' => $article->slug,
            'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationError('slug');
    }
    /**
     * @test
     */
    public function slug_must_only_contain_letters_numbers_and_dashes(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'slug' => '$%^&',
            'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationError('slug');
    }
    /**
     * @test
     */
    public function slug_must_not_contain_underscores(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'slug' => 'with_underscore',
            'content' => 'Contenido del articulo'
        ])->assertSee(trans('validation.no_underscore', [
            'attribute' => 'data.attributes.slug',
        ]))->assertJsonApiValidationError('slug');
    }
    /**
     * @test
     */
    public function slug_must_not_start_with_dashes(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'slug' => '-starts-with-dashes',
            'content' => 'Contenido del articulo'
        ])->assertSee(trans('validation.no_starting_dashes', [
            'attribute' => 'data.attributes.slug',
        ]))->assertJsonApiValidationError('slug');
    }
    /**
     * @test
     */
    public function slug_must_not_end_with_dashes(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'slug' => 'end-with-dashes-',
            'content' => 'Contenido del articulo'
        ])->assertSee(trans('validation.no_ending_dashes', [
            'attribute' => 'data.attributes.slug',
        ]))->assertJsonApiValidationError('slug');
    }
    /**
     * @test
     */
    public function content_is_required(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'slug' => 'nuevo-articulo'
        ])->assertJsonApiValidationError('content');
    }
    /**
     * @test
     */
    public function category_relationship_is_required(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'slug' => 'nuevo-articulo',
            'content' => 'Content of article'
        ])->assertJsonApiValidationError('relationships.category');
    }
    /**
     * @test
     */
    public function category_must_exist_in_database(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson(route('api.v1.articles.store'), [
            'title' => 'Nuevo Articulo',
            'slug' => 'nuevo-articulo',
            'content' => 'Content of article',
            '_relationships' => [
                'category' => Category::factory()->make()
            ]
        ])->assertJsonApiValidationError('relationships.category');
    }
}
