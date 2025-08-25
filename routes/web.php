<?php

use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Frontend\Auth\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Frontend\{
    BasePageController,
    CategoryController,
    ChapterController,
    HomeController,
    SocialAuthController,
    StoryController,
    LiveReactionController,
    UserController,
    CommentController,
    CommentReactionController,
    CommentEditController,
    LivechatController,
    NotificationController,
    SitemapController,
    DonationController
};
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Frontend\Auth\GoogleController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Frontend\RatingController;
use Livewire\Livewire;
use App\Http\Livewire\CommentSection;

use App\Http\Controllers\Frontend\DownloadController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
/*
|--------------------------------------------------------------------------

| Web Routes
|--------------------------------------------------------------------------

*/

// Clear cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "Cache is cleared";
})->name('clear.cache');


// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/the-loai/{slug}', [CategoryController::class, 'index'])->name('category');
Route::get('/truyen/{slug}', [StoryController::class, 'index'])->name('story');
Route::get('/{slugStory}/{slugChapter}', [ChapterController::class, 'index'])->name('chapter');

// Route kiểm tra cache
Route::get('/test-cache', [StoryController::class, 'testCache'])->name('test.cache');

Route::get('/tim-kiem', [HomeController::class, 'mainSearchStory'])->name('main.search.story');
Route::get('/phan-loai-theo-chuong', [StoryController::class, 'followChaptersCount'])->name('get.list.story.with.chapters.count');
Route::delete('/delete-tagged-notification/{notificationId}', [NotificationController::class, 'deleteTaggedNotification']);

// Route xem lịch sử edit comment (không cần đăng nhập)
Route::get('/comments/{comment}/edit-history', [CommentEditController::class, 'getEditHistory'])->name('comments.edit.history');

// Sitemap Routes
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
// Alternative sitemap routes
Route::get('sitemap-categories.xml', [SitemapController::class, 'categories'])->name('sitemap.categories.alt');
Route::get('sitemap-stories.xml', [SitemapController::class, 'stories'])->name('sitemap.stories.alt');
Route::get('sitemap-chapters.xml', [SitemapController::class, 'chapters'])->name('sitemap.chapters.alt');
// Sitemap cho các trang chính
Route::get('sitemap-main-pages.xml', [SitemapController::class, 'mainPages'])->name('sitemap.main.pages');


// Ajax Routes
Route::post('/ajax/get-chapters', [ChapterController::class, 'getChapters'])->name('get.chapters');
Route::post('/get-list-story-hot', [HomeController::class, 'getListStoryHot'])->name('get.list.story.hot');
Route::post('/get-list-story-hot-random', [HomeController::class, 'getListStoryHotRandom'])->name('get.list.story.hot.random');
Route::post('/ajax/search-story', [HomeController::class, 'searchStory'])->name('search.story');


// Donation routes for stories (chỉ để hiển thị)
Route::get('/truyen/{storySlug}/donations', [DonationController::class, 'getStoryDonations'])->name('story.donations');

// Legacy routes for backward compatibility
Route::post('/donate/store', [HomeController::class, 'storeDonate'])->name('donate.store');

// Authentication with Facebook
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToProvider']);
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('facebook.auth.callback');

Route::get('/search-users', [UserController::class, 'searchuser'])->name('user.search');

Route::get('/top-donate', [HomeController::class, 'index'])->name('user.top_donate');
// Laravel File Manager
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

// Search
Route::get('/search-chapter', [ChapterController::class, 'search'])->name('chapter.search');
Route::get('/chapter/{slug}', [ChapterController::class, 'show'])->name('chapter.show');
Route::get('/truyen/{slug}/search-chapters', [HomeController::class, 'searchChapters'])->name('chapters.search');


Route::post('/ratings', [RatingController::class, 'storeClient'])->name('ratings.store');

Route::get('/chat/messages', [ChatController::class, 'index']);
Route::post('/chat/send', [ChatController::class, 'store'])->middleware('auth');

// Middleware: Check Banned IP
Route::group(['middleware' => 'check.ip.ban'], function () {
    Route::middleware(['check.ban:ban_login'])->group(function () {
        //Route::post('/live/{id}/pin', [LiveController::class, 'pin'])->name('live.pin');

        Route::post('/comments/{comment}/react', [CommentReactionController::class, 'react'])->name('comments.react');
    });
});




Route::get('/{slugStory}/{slugChapter}/download-epub', [DownloadController::class, 'generateEpub'])
    ->name('download.epub')
    ->middleware('auth'); // Chỉ cho phép người dùng đã đăng nhập
// Guest routes
Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'Frontend.auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
    Route::view('/register', 'Frontend.auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::view('/forgot-password', 'Frontend.auth.forgot-password')->name('forgot-password');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
    Route::get('/logout', [AuthController::class, 'logout'])->name('user.logout');
    Route::post('/save-chapter', [AuthController::class, 'saveChapter'])->name('save.chapter');
    Route::get('/saved-chapters', [ChapterController::class, 'savedChapters'])->name('saved.chapters');
    Route::post('/remove-chapter', [AuthController::class, 'removeChapter'])->name('remove.chapter');
    Route::get('/verify-email/{token}', [UserController::class, 'verifyEmail'])->name('verify.email');
});

Livewire::component('comment-section', CommentSection::class);


// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Live chat & comments
    Route::middleware(['check.ban:ban_comment'])->group(function () {
        Route::post('replive/store', [LivechatController::class, 'storeClient'])->name('live.comment.store.client');
        Route::post('comment/store', [CommentController::class, 'storeClient'])->name('comment.store.client');
    });
    Route::post('live/store', [LivechatController::class, 'storeClient'])->name('user.comment.store.client');

    // Admin-only routes
    Route::middleware(['role.admin'])->group(function () {



        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    });
    // Routes liên quan đến comments

    //delete commen chapter
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
    // admin delete comment
    Route::delete('/delete-comments/{comment}', [CommentController::class, 'deleteComment'])->name('delete.comments');

    // Route ghim comment (pin/unpin)
    Route::post('/comments/{comment}/pin', [CommentController::class, 'togglePin'])->name('comments.pin');

    // Routes cho edit comment
    Route::get('/comments/{comment}/edit-form', [CommentEditController::class, 'getEditForm'])->name('comments.edit.form');
    Route::post('/comments/{comment}/edit', [CommentEditController::class, 'edit'])->name('comments.edit');

    // Route xóa nhiều users cùng lúc
    Route::post('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('admin.users.bulkDelete');

    // User profile
    Route::get('profile', [UserController::class, 'userProfile'])->name('profile');
    Route::post('update-profile/update-name-or-phone', [UserController::class, 'updateNameOrPhone'])->name('update.name.or.phone');
    Route::post('update-avatar', [UserController::class, 'updateAvatar'])->name('update.avatar');
    Route::post('update-password', [UserController::class, 'updatePassword'])->name('update.password');
    Route::post('update-profile/update-name-or-phone', [UserController::class, 'updateNameOrPhone'])->name('update.name.or.phone');

    Route::post('/admin/settings', [SettingsController::class, 'update'])->name('settings.update');

    //rating
    Route::post('/story/{story}/rate', [StoryController::class, 'rate'])->name('rate_story');
});

Route::get('login/{provider}', [GoogleController::class, 'redirectToGoogle'])->name('login.redirect');;
Route::get('login/{provider}/callback', [GoogleController::class, 'handleGoogleCallback'])->name('login.callback');

Route::post('/users/{id}/ban', [UserController::class, 'toggleBan']);

Route::get('/notifications', [NotificationController::class, 'getNotifications'])->middleware('auth');
Route::post('/notifications/read', [NotificationController::class, 'markAsRead'])->middleware('auth');
Route::get('admin/permissions/create', [PermissionController::class, 'create'])->name('admin.permissions.create');
Route::post('admin/permissions', [PermissionController::class, 'store'])->name('admin.permissions.store');
Route::get('admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
Route::post('roles', [RoleController::class, 'store'])->name('admin.roles.store');
Route::get('admin/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
Route::put('admin/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
