<?php

declare(strict_types=1);

namespace Jcf\BoostForKiro\Console;

use Illuminate\Console\Command;
use Jcf\BoostForKiro\Hooks\HookInstaller;
use Jcf\BoostForKiro\Hooks\HookWriter;

class InstallHooksCommand extends Command
{
    protected $signature = 'boost:kiro-hooks';

    protected $description = 'Install Boost MCP prompts as Kiro agent hooks';

    public function handle(HookInstaller $installer): int
    {
        $prompts = $installer->prompts();

        if ($prompts->isEmpty()) {
            $this->info('No eligible prompts found.');

            return self::SUCCESS;
        }

        $this->info("Installing {$prompts->count()} prompt(s) as Kiro hooks...");

        $results = $installer->install();

        foreach ($results as $name => $status) {
            $label = match ($status) {
                HookWriter::WRITTEN => '✓ created',
                HookWriter::UPDATED => '✓ updated',
                HookWriter::FAILED => '✗ failed',
            };

            $this->line("  {$name} ... {$label}");
        }

        $this->newLine();
        $this->info('Done. Hooks are available as user-triggered actions in Kiro.');

        return self::SUCCESS;
    }
}
