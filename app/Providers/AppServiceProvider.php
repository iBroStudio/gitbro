<?php

namespace App\Providers;

use Dotenv;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use OwenVoke\LaravelXdg\Xdg;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $homeConfigDirectory = App::environment('production')
            ? app(Xdg::class)->getHomeConfigDirectory().'/gitbro/'
            : base_path('playground').'/gitbro/';

        Config::set('logging.channels.single.path', \Phar::running()
            ? dirname(\Phar::running(false)) . $homeConfigDirectory . 'gitbro.log'
            : storage_path('logs/gitbro.log'));

        Config::set('app.homeConfigDirectory', $homeConfigDirectory);

        Config::set('app.workingDirectory',
            App::environment('production')
                ? getcwd()
                : base_path('playground')
        );

        Config::set('app.repositoryDirectory',
            App::environment('production')
                ? getcwd()
                : base_path('playground').'/test'
        );

        if (File::exists($homeConfigDirectory.'.env')) {
            $dotenv = Dotenv\Dotenv::createImmutable($homeConfigDirectory);
            $dotenv->load();
            Config::set('github.connections.main.token', env('GITHUB_PERSONAL_ACCESS_TOKEN'));
        }

        if (File::exists($homeConfigDirectory.'git.php')) {
            Config::set('git', array_merge(
                Config::get('git', []),
                require $homeConfigDirectory.'git.php'
            ));
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
