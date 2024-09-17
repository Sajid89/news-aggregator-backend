<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    // Fetch all categories
    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        return $this->success($categories, 'Categories fetched successfully');
    }
}
