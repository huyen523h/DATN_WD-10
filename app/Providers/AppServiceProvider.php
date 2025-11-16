<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Review; 
use App\Observers\ReviewObserver; 
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
      // đoạn này để kích hoạt OBSERVER
        Review::observe(ReviewObserver::class);
        Paginator::useBootstrapFive();
    }
}
