<?php

namespace App\Services;

use App\Contracts\UserFavoriteInterface;
use App\Models\UserFavorite;
use App\Models\Article;

class UserFavoriteService implements UserFavoriteInterface
{
    /**
     * Save an article to the user's favorites.
     *
     * @param int $userId
     * @param int $articleId
     * @return UserFavorite
     */
    public function saveFavorite($userId, $articleId)
    {
        // Check if the article is already favorited by the user
        $existingFavorite = UserFavorite::where('user_id', $userId)
            ->where('article_id', $articleId)->first();

        if ($existingFavorite) {
            throw new \Exception('Article already added to favorites');
        }

        // Save the new favorite
        return UserFavorite::create([
            'user_id' => $userId,
            'article_id' => $articleId,
        ]);
    }

    /**
     * Get all favorite articles for a user.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUserFavorites($request)
    {
        $userId = $request->get('user_id');

        // Get the article IDs from the user favorites
        $articleIds = UserFavorite::where('user_id', $userId)->pluck('article_id');

        // Fetch the articles based on those article IDs
        return Article::whereIn('id', $articleIds)->paginate($request->get('per_page', 10));
    }
}
