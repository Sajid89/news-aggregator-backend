<?php

namespace App\Services;

use App\Contracts\ArticleServiceInterface;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleService implements ArticleServiceInterface
{
    public function getArticles(Request $request)
    {
        $query = Article::query();

        // Search by keyword
        if ($request->has('keyword')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->keyword . '%')
                ->orWhere('content', 'like', '%' . $request->keyword . '%');
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Filter by source
        if ($request->has('source')) {
            $query->whereHas('source', function ($q) use ($request) {
                $q->where('name', $request->source);
            });
        }

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('published_at', $request->date);
        }

        return $query->with('source', 'category')->paginate($request->get('per_page', 10));
    }

    public function getArticleById($id)
    {
        return Article::with('source', 'category')->findOrFail($id);
    }
}