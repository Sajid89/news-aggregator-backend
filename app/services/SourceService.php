<?php

namespace App\Services;

use App\Models\Source;

class SourceService
{
    public function getAllSources()
    {
        return Source::skip(18)->take(PHP_INT_MAX)->get();
    }
}