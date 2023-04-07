<?php

namespace App\JsonApi\Traits;

use App\JsonApi\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

trait JsonApiResource
{
    abstract public function toJsonApi(): array;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return Document::type($this->getResourceType())
            ->id($this->resource->getRouteKey())
            ->attributes($this->filterAttributes($this->toJsonApi()))
            ->relationshipLinks($this->getRelationshipLinks())
            ->links([
                'self' => route('api.v1.' . $this->getResourceType() . '.show', $this->resource)
            ])->get('data');
    }
    public function getRelationshipLinks() : array
    {
        return [];
    }
    public function withResponse(Request $request, JsonResponse $response)
    {
        $response->header(
            'Location',
            route('api.v1.' . $this->getResourceType() . '.show', $this->resource)
        );
    }
    public function filterAttributes(array $attributes): array
    {
        return array_filter($attributes, function ($value) {
            if (request()->isNotFilled('fields')) {
                return $this;
            }

            $fields = explode(',', request('fields.' . $this->getResourceType()));

            if ($value === $this->getRouteKey()) {
                return in_array($this->getRouteKeyName(), $fields);
            }

            return $value;
        });
    }
    public static function collection($resource): AnonymousResourceCollection
    {
        $collection = parent::collection($resource);

        $collection->with['links'] = ['self' => $resource->path()];

        return $collection;
    }
}
