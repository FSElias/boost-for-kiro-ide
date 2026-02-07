<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;
use Laravel\Boost\Install\Enums\Platform;

/**
 * Tests for the boost:install command integration with Kiro IDE.
 *
 * These tests verify that:
 * - Kiro IDE is properly registered and can be retrieved
 * - Configuration paths are correct for file creation
 * - Detection configuration works for different platforms
 *
 * Validates: Requirements 3.2, 4.3, 7.3, 11.1, 11.2, 12.3
 *
 * Note: These tests verify the components that boost:install uses.
 * Manual testing of the actual command should be done in a real Laravel project.
 */
describe('Boost Install Command Integration', function () {
    it('kiro agent is registered with Boost', function () {
        // Verify that Kiro is registered as an agent
        $codeEnvironments = Boost::getCodeEnvironments();

        expect($codeEnvironments)->toHaveKey('kiro')
            ->and($codeEnvironments['kiro'])->toBe(Kiro::class);
    });

    it('kiro agent can be instantiated from registry', function () {
        $codeEnvironments = Boost::getCodeEnvironments();
        $kiroClass = $codeEnvironments['kiro'];

        $kiro = app($kiroClass);

        expect($kiro)->toBeInstanceOf(Kiro::class)
            ->and($kiro->name())->toBe('kiro')
            ->and($kiro->displayName())->toBe('Kiro');
    });

    it('kiro provides correct configuration paths for file creation', function () {
        $kiro = app(Kiro::class);

        $mcpPath = $kiro->mcpConfigPath();
        $guidelinesPath = $kiro->guidelinesPath();

        expect($mcpPath)->toBe('.kiro/settings/mcp.json')
            ->and($guidelinesPath)->toBe('.kiro/steering/laravel-boost.md');
    });

    it('kiro provides system detection config for all platforms', function () {
        $kiro = app(Kiro::class);

        // Test macOS detection
        $darwinConfig = $kiro->systemDetectionConfig(Platform::Darwin);
        expect($darwinConfig)->toHaveKey('paths')
            ->and($darwinConfig['paths'])->toContain('/Applications/Kiro.app');

        // Test Linux detection
        $linuxConfig = $kiro->systemDetectionConfig(Platform::Linux);
        expect($linuxConfig)->toHaveKey('paths')
            ->and($linuxConfig['paths'])->toBeArray()
            ->and(count($linuxConfig['paths']))->toBeGreaterThan(0);

        // Test Windows detection
        $windowsConfig = $kiro->systemDetectionConfig(Platform::Windows);
        expect($windowsConfig)->toHaveKey('paths')
            ->and($windowsConfig['paths'])->toBeArray()
            ->and(count($windowsConfig['paths']))->toBeGreaterThan(0);
    });

    it('kiro provides project detection config', function () {
        $kiro = app(Kiro::class);

        $projectConfig = $kiro->projectDetectionConfig();

        expect($projectConfig)->toHaveKey('paths')
            ->and($projectConfig['paths'])->toContain('.kiro');
    });

    it('kiro frontmatter setting is configured', function () {
        $kiro = app(Kiro::class);

        expect($kiro->frontmatter())->toBeFalse();
    });

    it('kiro implements required interfaces for boost:install', function () {
        $kiro = app(Kiro::class);

        expect($kiro)->toBeInstanceOf(\Laravel\Boost\Install\CodeEnvironment\CodeEnvironment::class)
            ->and($kiro)->toBeInstanceOf(\Laravel\Boost\Contracts\Agent::class)
            ->and($kiro)->toBeInstanceOf(\Laravel\Boost\Contracts\McpClient::class);
    });
});
