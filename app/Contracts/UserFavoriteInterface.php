<?php

namespace App\Contracts;

interface UserFavoriteInterface
{
    public function saveFavorite($userId, $articleId);

    public function getUserFavorites($userId);
}
