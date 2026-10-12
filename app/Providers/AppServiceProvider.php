<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Site-wide data ($site settings, $menuCategories) for every public page.
        // Child views use $site inside @section(), so the composer is attached to them too.
        $shared = null;

        View::composer(
            ['layouts.app', 'home', 'contact', 'yatras.*', 'blog.*', 'pages.*'],
            function ($view) use (&$shared) {
                if ($shared === null) {
                    try {
                        $shared = [
                            'site' => Setting::pluck('value', 'key'),
                            'menuCategories' => Category::orderBy('sort_order')->get(),
                        ];
                    } catch (\Throwable $e) {
                        $shared = ['site' => collect(), 'menuCategories' => collect()];
                    }
                }
                $view->with($shared);
            }
        );
    }
}
