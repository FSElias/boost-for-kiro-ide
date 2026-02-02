<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Install\Enums\Platform;

/**
 * Example 7: boost:install Detecta Kiro IDE Automaticamente
 *
 * Valida: Requisitos 7.1, 7.2, 7.3
 *
 * Testa systemDetectionConfig() para cada plataforma
 * Testa projectDetectionConfig() retorna [".kiro"]
 */
describe('Installation Detection Integration', function () {
    beforeEach(function () {
        $this->kiro = app(Kiro::class);
    });

    it('provides system detection config for darwin platform', function () {
        $config = $this->kiro->systemDetectionConfig(Platform::Darwin);

        expect($config)
            ->toBeArray()
            ->toHaveKey('paths')
            ->and($config['paths'])
            ->toBeArray()
            ->not->toBeEmpty()
            ->toContain('/Applications/Kiro.app');
    });

    it('provides system detection config for linux platform', function () {
        $config = $this->kiro->systemDetectionConfig(Platform::Linux);

        expect($config)
            ->toBeArray()
            ->toHaveKey('paths')
            ->and($config['paths'])
            ->toBeArray()
            ->not->toBeEmpty()
            ->toContain('/opt/kiro')
            ->toContain('/usr/local/bin/kiro')
            ->toContain('~/.local/bin/kiro');
    });

    it('provides system detection config for windows platform', function () {
        $config = $this->kiro->systemDetectionConfig(Platform::Windows);

        expect($config)
            ->toBeArray()
            ->toHaveKey('paths')
            ->and($config['paths'])
            ->toBeArray()
            ->not->toBeEmpty()
            ->toContain('%ProgramFiles%\\Kiro')
            ->toContain('%LOCALAPPDATA%\\Programs\\Kiro');
    });

    it('provides system detection config for all platforms', function () {
        $platforms = [Platform::Darwin, Platform::Linux, Platform::Windows];

        foreach ($platforms as $platform) {
            $config = $this->kiro->systemDetectionConfig($platform);

            expect($config)
                ->toBeArray()
                ->toHaveKey('paths')
                ->and($config['paths'])
                ->toBeArray()
                ->not->toBeEmpty();
        }
    });

    it('provides project detection config with .kiro directory', function () {
        $config = $this->kiro->projectDetectionConfig();

        expect($config)
            ->toBeArray()
            ->toHaveKey('paths')
            ->and($config['paths'])
            ->toBeArray()
            ->not->toBeEmpty()
            ->toContain('.kiro');
    });

    it('project detection config contains only .kiro path', function () {
        $config = $this->kiro->projectDetectionConfig();

        expect($config['paths'])
            ->toHaveCount(1)
            ->and($config['paths'][0])
            ->toBe('.kiro');
    });

    it('system detection paths are platform-specific', function () {
        $darwinConfig = $this->kiro->systemDetectionConfig(Platform::Darwin);
        $linuxConfig = $this->kiro->systemDetectionConfig(Platform::Linux);
        $windowsConfig = $this->kiro->systemDetectionConfig(Platform::Windows);

        // Darwin should have macOS-specific paths
        expect($darwinConfig['paths'])
            ->toContain('/Applications/Kiro.app');

        // Linux should have Linux-specific paths
        expect($linuxConfig['paths'])
            ->toContain('/opt/kiro');

        // Windows should have Windows-specific paths
        expect($windowsConfig['paths'])
            ->toContain('%ProgramFiles%\\Kiro');

        // Paths should be different across platforms
        expect($darwinConfig['paths'])
            ->not->toBe($linuxConfig['paths'])
            ->not->toBe($windowsConfig['paths']);
    });

    it('detection configs are consistent across multiple calls', function () {
        $config1 = $this->kiro->systemDetectionConfig(Platform::Darwin);
        $config2 = $this->kiro->systemDetectionConfig(Platform::Darwin);
        $projectConfig1 = $this->kiro->projectDetectionConfig();
        $projectConfig2 = $this->kiro->projectDetectionConfig();

        expect($config1)
            ->toBe($config2)
            ->and($projectConfig1)
            ->toBe($projectConfig2);
    });
});
