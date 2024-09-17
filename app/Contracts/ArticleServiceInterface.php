<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface ArticleServiceInterface
{
    public function getArticles(Request $request);

    public function getArticleById($id);
}
