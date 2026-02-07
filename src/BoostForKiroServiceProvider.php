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
     * Register any package services.
     */
    public function register(): void
    {
        // Register Kiro early in the register phase
        // This ensures it's available when boost:install command runs
        $this->app->booted(function () {
            if (! class_exists(Boost::class) || ! class_exists(BoostManager::class)) {
                return;
            }

            try {
                // Try to get the BoostManager and register Kiro
                $manager = $this->app->make(BoostManager::class);
                
                if ($manager instanceof BoostManager) {
                    $manager->registerCodeEnvironment('kiro', Kiro::class);
                }
            } catch (\Throwable) {
                // Silently fail if BoostManager isn't available
                // This can happen if Boost is disabled or not properly configured
            }
        });
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        // Keep boot method for backwards compatibility
        // The actual registration happens in register() now
    }
}
