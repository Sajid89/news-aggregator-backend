<?php

namespace App\Http\Controllers;

use App\Contracts\ArticleServiceInterface;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $articleService;

    public function __construct(ArticleServiceInterface $articleService)
    {
        $this->articleService = $articleService;
    }

    // Fetch all articles with optional filtering
    public function index(Request $request)
    {
        $articles = $this->articleService->getArticles($request);
        return $this->success($articles, 'Articles fetched successfully');
    }

    // Fetch a single article by ID
    public function show($id)
    {
        $article = $this->articleService->getArticleById($id);
        return $this->success($article, 'Article fetched successfully');
    }
}