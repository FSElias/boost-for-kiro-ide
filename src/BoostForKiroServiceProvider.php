<?php

declare(strict_types=1);

namespace Jcf\BoostForKiro;

use Illuminate\Support\ServiceProvider;
use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;

class BoostForKiroServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (class_exists(Boost::class)) {
            Boost::registerCodeEnvironment('kiro', Kiro::class);
        }
    }
}
