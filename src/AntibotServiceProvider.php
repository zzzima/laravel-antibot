<?php

declare(strict_types=1);

namespace Zima\Antibot;

use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Zima\Antibot\Http\Middleware\Antibot as AntibotMiddleware;
use Zima\Antibot\View\Components\Antibot as AntibotComponent;

class AntibotServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'antibot');

        $this->publishes([
            __DIR__ . '/../config/antibot.php' => config_path('antibot.php'),
        ], 'antibot-config');

        Blade::component('zima-antibot', AntibotComponent::class);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/antibot.php', 'antibot');

        if (Route::hasMacro('antibot')) {
            return;
        }

        Route::macro('antibot', function (array $requiredFields = [], array $contentFields = []) {
            /** @var Route $this */
            $this->middleware(AntibotMiddleware::class . ':' . serialize([
                'requiredFields' => $requiredFields,
                'contentFields' => $contentFields,
            ]));

            return $this;
        });
    }
}
