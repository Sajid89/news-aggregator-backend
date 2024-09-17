<?php

namespace App\Http\Controllers;

use App\Services\SourceService;

class SourceController extends Controller
{
    protected $sourceService;

    public function __construct(SourceService $sourceService)
    {
        $this->sourceService = $sourceService;
    }

    // Fetch all sources
    public function index()
    {
        $sources = $this->sourceService->getAllSources();
        return $this->success($sources, 'Sources fetched successfully');
    }
}
