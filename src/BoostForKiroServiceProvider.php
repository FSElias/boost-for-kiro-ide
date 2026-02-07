<?php

declare(strict_types=1);

namespace Jcf\BoostForKiro;

use Illuminate\Support\ServiceProvider;
use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;
use Laravel\Boost\BoostManager;

class BoostForKiroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * This method registers the Kiro IDE code environment with Laravel Boost
     * using the extension hook provided by the package.
     */
    public function boot(): void
    {
        // Only register if Boost classes exist and BoostManager is available
        if (! class_exists(Boost::class) || ! class_exists(BoostManager::class)) {
            return;
        }

        // Try to register, but don't fail if BoostManager isn't bound yet
        try {
            if ($this->app->bound(BoostManager::class)) {
                Boost::registerCodeEnvironment('kiro', Kiro::class);
            }
        } catch (\Throwable) {
            // Silently fail if registration isn't possible
            // This can happen if Boost is disabled or not properly configured
        }
    }
}
