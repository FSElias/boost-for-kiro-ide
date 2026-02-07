<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;
use Laravel\Boost\Contracts\SupportsGuidelines;
use Laravel\Boost\Contracts\SupportsMcp;
use Laravel\Boost\Contracts\SupportsSkills;
use Laravel\Boost\Install\AgentsDetector;
use Laravel\Boost\Install\Enums\McpInstallationStrategy;
use Laravel\Boost\Install\Enums\Platform;

describe('Boost Install Command Integration', function () {
    it('kiro agent is registered with Boost', function () {
        $agents = Boost::getAgents();

        expect($agents)->toHaveKey('kiro')
            ->and($agents['kiro'])->toBe(Kiro::class);
    });

    it('kiro agent can be instantiated from registry', function () {
        $agents = Boost::getAgents();
        $kiro = app($agents['kiro']);

        expect($kiro)->toBeInstanceOf(Kiro::class)
            ->and($kiro->name())->toBe('kiro')
            ->and($kiro->displayName())->toBe('Kiro');
    });

    it('kiro provides correct configuration paths for file creation', function () {
        $kiro = app(Kiro::class);

        expect($kiro->mcpConfigPath())->toBe('.kiro/settings/mcp.json')
            ->and($kiro->guidelinesPath())->toBe('.kiro/steering/laravel-boost.md')
            ->and($kiro->skillsPath())->toBe('.kiro/skills');
    });

    it('kiro provides system detection config for all platforms', function () {
        $kiro = app(Kiro::class);

        $darwinConfig = $kiro->systemDetectionConfig(Platform::Darwin);
        expect($darwinConfig)->toHaveKey('paths')
            ->and($darwinConfig['paths'])->toContain('/Applications/Kiro.app');

        $linuxConfig = $kiro->systemDetectionConfig(Platform::Linux);
        expect($linuxConfig)->toHaveKey('paths')
            ->and($linuxConfig['paths'])->toBeArray()->not->toBeEmpty();

        $windowsConfig = $kiro->systemDetectionConfig(Platform::Windows);
        expect($windowsConfig)->toHaveKey('paths')
            ->and($windowsConfig['paths'])->toBeArray()->not->toBeEmpty();
    });

    it('kiro provides project detection config', function () {
        $kiro = app(Kiro::class);

        expect($kiro->projectDetectionConfig())
            ->toHaveKey('paths')
            ->and($kiro->projectDetectionConfig()['paths'])->toContain('.kiro');
    });

    it('kiro implements required interfaces for boost:install', function () {
        $kiro = app(Kiro::class);

        expect($kiro)
            ->toBeInstanceOf(\Laravel\Boost\Install\Agents\Agent::class)
            ->toBeInstanceOf(SupportsGuidelines::class)
            ->toBeInstanceOf(SupportsMcp::class)
            ->toBeInstanceOf(SupportsSkills::class);
    });

    it('AgentsDetector includes Kiro instance', function () {
        $detector = app(AgentsDetector::class);
        $agents = $detector->getAgents();

        $kiroInstance = $agents->first(fn ($agent) => $agent->name() === 'kiro');

        expect($kiroInstance)->not->toBeNull()
            ->and($kiroInstance)->toBeInstanceOf(Kiro::class)
            ->and($kiroInstance)->toBeInstanceOf(SupportsGuidelines::class)
            ->and($kiroInstance)->toBeInstanceOf(SupportsMcp::class);
    });

    it('kiro uses FILE mcp installation strategy for installMcp', function () {
        $kiro = app(Kiro::class);

        expect($kiro->mcpInstallationStrategy())->toBe(McpInstallationStrategy::FILE)
            ->and($kiro->mcpConfigPath())->toBe('.kiro/settings/mcp.json')
            ->and($kiro->mcpConfigKey())->toBe('mcpServers');
    });

    it('installMcp writes config file correctly', function () {
        $kiro = app(Kiro::class);

        $tempDir = sys_get_temp_dir().'/kiro-test-'.uniqid();
        mkdir($tempDir.'/.kiro/settings', 0777, true);

        $originalDir = getcwd();
        chdir($tempDir);

        try {
            $result = $kiro->installMcp('laravel-boost', 'php', ['artisan', 'boost:mcp'], ['APP_ENV' => 'local']);

            expect($result)->toBeTrue();

            $configFile = $tempDir.'/.kiro/settings/mcp.json';
            expect(file_exists($configFile))->toBeTrue();

            $config = json_decode(file_get_contents($configFile), true);
            expect($config)->toHaveKey('mcpServers')
                ->and($config['mcpServers'])->toHaveKey('laravel-boost')
                ->and($config['mcpServers']['laravel-boost']['command'])->toBe('php')
                ->and($config['mcpServers']['laravel-boost']['args'])->toBe(['artisan', 'boost:mcp'])
                ->and($config['mcpServers']['laravel-boost']['env'])->toBe(['APP_ENV' => 'local']);
        } finally {
            chdir($originalDir);
            if (file_exists($tempDir.'/.kiro/settings/mcp.json')) {
                unlink($tempDir.'/.kiro/settings/mcp.json');
            }
            if (is_dir($tempDir.'/.kiro/settings')) {
                rmdir($tempDir.'/.kiro/settings');
            }
            if (is_dir($tempDir.'/.kiro')) {
                rmdir($tempDir.'/.kiro');
            }
            if (is_dir($tempDir)) {
                rmdir($tempDir);
            }
        }
    });
});
