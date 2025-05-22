<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Frontend\Controller;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Story;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        return response()->view('Frontend.sitemap.index', [
            'categories' => Category::all(),
            'stories' => Story::all(),
            'chapters' => Chapter::all(),
        ])->header('Content-Type', 'text/xml');
    }

    public function categories()
    {
        return response()->view('Frontend.sitemap.categories', [
            'categories' => Category::all(),
        ])->header('Content-Type', 'text/xml');
    }

    public function stories()
    {
        return response()->view('Frontend.sitemap.stories', [
            'stories' => Story::all(),
        ])->header('Content-Type', 'text/xml');
    }

    public function chapters()
    {
        return response()->view('Frontend.sitemap.chapters', [
            'chapters' => Chapter::all(),
        ])->header('Content-Type', 'text/xml');
    }
} 