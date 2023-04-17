<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_owned_update_articles(): void
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author, ['article:update']);

        $response = $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update Articulo',
            'slug' => $article->slug,
            'content' => 'Update contenido del articulo'
        ]);

        $response->assertOk();

        $response->assertJsonApiResource($article, [
                'title'   => 'Update Articulo',
                'slug'    => $article->slug,
                'content' => 'Update contenido del articulo'
            ]
        );
    }
    /**
     * @test
     */
    public function cannot_update_articles_owned_by_other_users(): void
    {
        $article = Article::factory()->create();

        Sanctum::actingAs(User::factory()->create());

        $response = $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update Articulo',
            'slug' => $article->slug,
            'content' => 'Update contenido del articulo'
        ]);

        $response->assertForbidden();

        // $response->assertJsonApiResource($article, [
        //         'title'   => 'Update Articulo',
        //         'slug'    => $article->slug,
        //         'content' => 'Update contenido del articulo'
        //     ]
        // );
    }
    /**
     * @test
     */
    public function guests_cannot_update_article(): void
    {
        $article = Article::factory()->create();

        $response = $this->patchJson(route('api.v1.articles.update', $article))
            ->assertUnauthorized();

        $response->assertJsonApiError(
            title: 'Unauthenticated',
            detail: 'This action requires authentication.',
            status: '401'
        );
    }
    /**
     * @test
     */
    public function title_is_required(): void
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article), [
            'slug' => 'update-articulo',
            'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationError('title');
    }
    /**
     * @test
     */
    public function title_must_be_at_least_4_characters(): void
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Upd',
            'slug' => 'update-articulo',
            'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationError('title');
    }
    /**
     * @test
     */
    public function slug_is_required(): void
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update Articulo',
            'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationError('slug');
    }
    /**
     * @test
     */
    public function slug_must_be_unique(): void
    {
        $article1 = Article::factory()->create();
        $article2 = Article::factory()->create();

        Sanctum::actingAs($article1->author);

        $this->patchJson(route('api.v1.articles.update', $article1), [
            'title' => 'Update Articulo',
            'slug' => $article2->slug,
            'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationError('slug');
    }
    /**
     * @test
     */
    public function slug_must_only_contain_letters_numbers_and_dashes(): void
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update Articulo',
            'slug' => '$%^&',
            'content' => 'Contenido del articulo'
        ])->assertJsonApiValidationError('slug');
    }
    /**
     * @test
     */
    public function slug_must_not_contain_underscores(): void
    {
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update Articulo',
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
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update Articulo',
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
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update Articulo',
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
        $article = Article::factory()->create();

        Sanctum::actingAs($article->author);

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Update Articulo',
            'slug' => 'update-articulo'
        ])->assertJsonApiValidationError('content');
    }
}
