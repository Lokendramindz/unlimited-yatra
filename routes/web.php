<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\{BlogController, EnquiryController, HomeController, PageController, ReviewController, YatraController};
use App\Models\{Fleet, Page, Post, Yatra};
use Illuminate\Support\Facades\Route;

// ---------- Public website ----------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', function () {
    $urls = collect([
        ['loc' => route('home'), 'lastmod' => null],
        ['loc' => route('yatras.index'), 'lastmod' => null],
        ['loc' => route('blog.index'), 'lastmod' => null],
        ['loc' => route('contact'), 'lastmod' => null],
        ['loc' => route('guides'), 'lastmod' => null],
        ['loc' => route('fleet'), 'lastmod' => null],
        ['loc' => route('faqs'), 'lastmod' => null],
    ])
        ->merge(Page::where('is_active', true)->get(['slug', 'updated_at'])->map(fn ($page) => [
            'loc' => route('page.show', $page->slug), 'lastmod' => $page->updated_at,
        ]))
        ->merge(Yatra::active()->get(['slug', 'updated_at'])->map(fn ($yatra) => [
            'loc' => route('yatras.show', $yatra->slug), 'lastmod' => $yatra->updated_at,
        ]))
        ->merge(Fleet::where('is_active', true)->get(['slug', 'updated_at'])->map(fn ($fleet) => [
            'loc' => route('fleet.show', $fleet->slug), 'lastmod' => $fleet->updated_at,
        ]))
        ->merge(Post::where('is_active', true)->get(['slug', 'updated_at'])->map(fn ($post) => [
            'loc' => route('blog.show', $post->slug), 'lastmod' => $post->updated_at,
        ]));

    return response()->view('sitemap', compact('urls'))->header('Content-Type', 'application/xml; charset=UTF-8');
})->name('sitemap');
Route::get('/yatras', [YatraController::class, 'index'])->name('yatras.index');
Route::get('/yatras/{slug}', [YatraController::class, 'show'])->name('yatras.show');
Route::post('/yatras/{slug}/reviews', [ReviewController::class, 'storeYatra'])->middleware('throttle:5,1')->name('reviews.yatras.store');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/about-us', fn () => redirect()->route('page.show', 'about-us'))->name('about');
Route::get('/our-guides', [PageController::class, 'guides'])->name('guides');
Route::get('/transport-rental', [PageController::class, 'fleet'])->name('fleet');
Route::get('/transport-rental/{slug}', [PageController::class, 'fleetShow'])->name('fleet.show');
Route::post('/transport-rental/{slug}/reviews', [ReviewController::class, 'storeFleet'])->middleware('throttle:5,1')->name('reviews.fleet.store');
Route::redirect('/our-fleet', '/transport-rental', 301)->name('fleet.legacy');
Route::get('/our-fleet/{slug}', fn (string $slug) => redirect()->route('fleet.show', $slug, 301));
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
Route::get('/contact', [EnquiryController::class, 'create'])->name('contact');
Route::post('/enquiry', [EnquiryController::class, 'store'])->middleware('throttle:10,1')->name('enquiry.store');
Route::post('/subscribe', [EnquiryController::class, 'subscribe'])->middleware('throttle:10,1')->name('subscribe');

// ---------- Admin panel ----------
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [Admin\AuthController::class, 'login'])->middleware('throttle:10,1');

    Route::middleware('admin')->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings');
        Route::post('settings', [Admin\SettingController::class, 'update'])->name('settings.update');

        Route::get('{resource}', [Admin\ResourceController::class, 'index'])->name('resource.index');
        Route::get('{resource}/create', [Admin\ResourceController::class, 'create'])->name('resource.create');
        Route::post('{resource}', [Admin\ResourceController::class, 'store'])->name('resource.store');
        Route::get('{resource}/{id}/edit', [Admin\ResourceController::class, 'edit'])->name('resource.edit')->whereNumber('id');
        Route::put('{resource}/{id}', [Admin\ResourceController::class, 'update'])->name('resource.update')->whereNumber('id');
        Route::delete('{resource}/{id}', [Admin\ResourceController::class, 'destroy'])->name('resource.destroy')->whereNumber('id');
    });
});
