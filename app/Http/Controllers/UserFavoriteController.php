<?php

namespace App\Http\Controllers;

use App\Contracts\UserFavoriteInterface;
use App\Http\Requests\UserFavoriteRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFavoriteController extends Controller
{
    protected $userFavoriteRequest;
    protected $userFavoriteService;

    public function __construct(
        UserFavoriteRequest $userFavoriteRequest,
        UserFavoriteInterface $userFavoriteService
    )
    {
        $this->userFavoriteRequest = $userFavoriteRequest;
        $this->userFavoriteService = $userFavoriteService;
    }

    /**
     * Save an article to the user's favorites.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveFavorite(Request $request)
    {
        $this->userFavoriteRequest->validateSaveFavorite($request);
        $userId = $request->input('user_id');
        $articleId = $request->input('article_id');

        $favorite = $this->userFavoriteService->saveFavorite($userId, $articleId);

        $this->success($favorite, 'Article added to favorites.', 201);
    }

    /**
     * Get the user's favorite articles.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserFavorites(Request $request)
    {
        $this->userFavoriteRequest->validateGetUserFavorites($request);
        $favorites = $this->userFavoriteService->getUserFavorites($request);

        if ($favorites->isEmpty()) {
            return $this->error('No favorite articles found.', 404);
        }

        return $this->success($favorites, 'Favorite articles retrieved successfully.');
    }
}
