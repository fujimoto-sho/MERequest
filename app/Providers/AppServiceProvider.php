<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // 本番環境以外ではSQLログを出力する
        if (config('app.env') !== 'production') {
            \DB::listen(function ($query) {
                \Log::info("Query Time:{$query->time}s] $query->sql");
            });
        }

        // リポジトリパターンのバインディング
        $this->app->bind(\App\Repositories\Contracts\UserContract::class,
            \App\Repositories\Eloquents\EloquentUserRepository::class);
        $this->app->bind(\App\Repositories\Contracts\PropositionContract::class,
            \App\Repositories\Eloquents\EloquentPropositionRepository::class);
        $this->app->bind(\App\Repositories\Contracts\MessageContract::class,
            \App\Repositories\Eloquents\EloquentMessageRepository::class);
        $this->app->bind(\App\Repositories\Contracts\DirectMessageContract::class,
            \App\Repositories\Eloquents\EloquentDirectMessageRepository::class);

        // route()やurl()で生成したURLをhttps化
        if (env('APP_ENV') == 'production' || env('APP_ENV') == 'staging') {
            \URL::forceScheme('https');
        }
    }
}
