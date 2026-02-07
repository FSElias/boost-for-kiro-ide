<?php

declare(strict_types=1);

namespace Jcf\BoostForKiro\CodeEnvironment;

use Laravel\Boost\Contracts\Agent;
use Laravel\Boost\Contracts\McpClient;
use Laravel\Boost\Install\CodeEnvironment\CodeEnvironment;
use Laravel\Boost\Install\Enums\McpInstallationStrategy;
use Laravel\Boost\Install\Enums\Platform;

class Kiro extends CodeEnvironment implements Agent, McpClient
{
    public function name(): string
    {
        return 'kiro';
    }

    public function displayName(): string
    {
        return 'Kiro';
    }

    public function systemDetectionConfig(Platform $platform): array
    {
        return match ($platform) {
            Platform::Darwin => [
                'paths' => ['/Applications/Kiro.app'],
            ],
            Platform::Linux => [
                'paths' => [
                    '/opt/kiro',
                    '/usr/local/bin/kiro',
                    '~/.local/bin/kiro',
                ],
            ],
            Platform::Windows => [
                'paths' => [
                    '%ProgramFiles%\\Kiro',
                    '%LOCALAPPDATA%\\Programs\\Kiro',
                ],
            ],
        };
    }

    public function projectDetectionConfig(): array
    {
        return [
            'paths' => ['.kiro'],
        ];
    }

    public function mcpInstallationStrategy(): McpInstallationStrategy
    {
        return McpInstallationStrategy::FILE;
    }

    public function mcpConfigPath(): string
    {
        return '.kiro/settings/mcp.json';
    }

    public function guidelinesPath(): string
    {
        return '.kiro/steering/laravel-boost.md';
    }

    public function frontmatter(): bool
    {
        return false;
    }
}
