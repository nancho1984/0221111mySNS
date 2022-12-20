<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //ページ跨ぎ（ページネーター）を使うためにbootstrapを使うよ宣言
        Paginator::useBootstrap();
        
        \URL::forceScheme('https');
        $this->app['request']->server->set('HTTPS','on');
    }
}
