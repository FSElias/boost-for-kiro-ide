<?php

declare(strict_types=1);

namespace Jcf\BoostForKiro;

use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Jcf\BoostForKiro\Console\InstallHooksCommand;
use Jcf\BoostForKiro\Hooks\HookInstaller;
use Jcf\BoostForKiro\Hooks\HookWriter;
use Jcf\BoostForKiro\Hooks\PromptServer;
use Jcf\BoostForKiro\Hooks\PromptToHookConverter;
use Laravel\Boost\Boost;

class BoostForKiroServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HookInstaller::class, function ($app): HookInstaller {
            $kiro = $app->make(Kiro::class);

            return new HookInstaller(
                $app->make(PromptServer::class),
                new PromptToHookConverter,
                new HookWriter($kiro->hooksPath()),
            );
        });
    }

    public function boot(): void
    {
        if (class_exists(Boost::class)) {
            $agents = Boost::getAgents();
            if (! array_key_exists('kiro', $agents)) {
                Boost::registerAgent('kiro', Kiro::class);
            }
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallHooksCommand::class,
            ]);

            Event::listen(CommandFinished::class, function (CommandFinished $event): void {
                if (! in_array($event->command, ['boost:install', 'boost:update'])) {
                    return;
                }

                if ($event->exitCode !== 0) {
                    return;
                }

                if (! config('boost.agents.kiro.auto_sync_hooks', true)) {
                    return;
                }

                $this->app->make(HookInstaller::class)->install();
            });
        }
    }
}
