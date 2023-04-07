<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::jsonPaginate();

        return CategoryResource::collection($categories);
    }
    public function show($category) : JsonResource
    {
        $category = Category::where('slug', $category)->firstOrFail();

        return CategoryResource::make($category);
    }
}
