<?php

namespace App\Providers;

use App\Http\ViewComposers\ChapterComposer;
use App\Http\ViewComposers\HomeComposer;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\ViewComposers\LayoutComposer;
use App\Models\Status;
use App\Models\Chapter;

use App\Models\User;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {

        // Calculate stats
        $stats = [
            'total_chapters' => Chapter::selectRaw('story_id, COUNT(*) as count')
        ->groupBy('story_id')
        ->pluck('count', 'story_id'),
            'total_views' => Chapter::whereNotNull('views')
        ->selectRaw('story_id, SUM(views) as total_views')
        ->groupBy('story_id')
        ->pluck('total_views', 'story_id')
        ->toArray(),
            'ratings' => [
                'count' => User::whereNotNull('rating')->count(),
                'average' => number_format(User::whereNotNull('rating')->avg('rating') ?? 0, 1)
            ]
        ];

        // Get story status
        $status = Status::first();
        Breadcrumbs::for('home', function ($trail) {
            $trail->push('Tổng quan', route('admin.dashboard.index'));
        });

        Paginator::useBootstrap();
        if ($request->server->get('HTTPS') === 'on') {
            URL::forceScheme('https');
        }

        if (in_array(config('app.env', 'local'), ['production', 'staging'])) {
            URL::forceScheme('https');
        }
        Carbon::setLocale('vi');
        setlocale(LC_TIME, 'vi_VN.UTF-8');

        View::composer('Frontend.layouts.default', LayoutComposer::class);
        View::composer('Frontend.home', HomeComposer::class);
        View::composer('Frontend.category', LayoutComposer::class);
        View::composer('Frontend.follow_chapter_count', LayoutComposer::class);
        View::composer('Frontend.story', HomeComposer::class);
        View::composer('Frontend.chapter', ChapterComposer::class);
        // Share with all views
        View::share('stats', $stats);
        View::share('status', $status);
    }
}
