<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Install\Enums\Platform;

/**
 * Property 1: Métodos de Interface Executam Corretamente
 *
 * Valida: Requisitos 2.3
 *
 * Para qualquer instância da classe Kiro, chamar qualquer método definido pelas
 * interfaces Agent, McpClient ou CodeEnvironment deve executar sem lançar exceções
 * e retornar valores válidos do tipo esperado.
 */
describe('Kiro interface implementation', function () {
    beforeEach(function () {
        $this->kiro = app(Kiro::class);
    });

    it('implements name method correctly', function () {
        $result = $this->kiro->name();

        expect($result)
            ->toBeString()
            ->not->toBeEmpty();
    });

    it('implements displayName method correctly', function () {
        $result = $this->kiro->displayName();

        expect($result)
            ->toBeString()
            ->not->toBeEmpty();
    });

    it('implements systemDetectionConfig method for all platforms', function () {
        $platforms = [Platform::Darwin, Platform::Linux, Platform::Windows];

        foreach ($platforms as $platform) {
            $result = $this->kiro->systemDetectionConfig($platform);

            expect($result)
                ->toBeArray()
                ->toHaveKey('paths')
                ->and($result['paths'])
                ->toBeArray()
                ->not->toBeEmpty();
        }
    });

    it('implements projectDetectionConfig method correctly', function () {
        $result = $this->kiro->projectDetectionConfig();

        expect($result)
            ->toBeArray()
            ->toHaveKey('paths')
            ->and($result['paths'])
            ->toBeArray()
            ->not->toBeEmpty();
    });

    it('implements mcpConfigPath method correctly', function () {
        $result = $this->kiro->mcpConfigPath();

        expect($result)
            ->toBeString()
            ->not->toBeEmpty()
            ->toContain('.kiro');
    });

    it('implements guidelinesPath method correctly', function () {
        $result = $this->kiro->guidelinesPath();

        expect($result)
            ->toBeString()
            ->not->toBeEmpty()
            ->toContain('.kiro');
    });

    it('implements frontmatter method correctly', function () {
        $result = $this->kiro->frontmatter();

        expect($result)->toBeBool();
    });

    it('implements useAbsolutePathForMcp method correctly', function () {
        $result = $this->kiro->useAbsolutePathForMcp();

        expect($result)->toBeBool();
    });

    it('implements getPhpPath method correctly', function () {
        $result = $this->kiro->getPhpPath();

        expect($result)
            ->toBeString()
            ->not->toBeEmpty();

        // Test with forceAbsolutePath parameter
        $resultAbsolute = $this->kiro->getPhpPath(true);

        expect($resultAbsolute)
            ->toBeString()
            ->not->toBeEmpty();
    });

    it('implements getArtisanPath method correctly', function () {
        $result = $this->kiro->getArtisanPath();

        expect($result)
            ->toBeString()
            ->not->toBeEmpty();

        // Test with forceAbsolutePath parameter
        $resultAbsolute = $this->kiro->getArtisanPath(true);

        expect($resultAbsolute)
            ->toBeString()
            ->not->toBeEmpty();
    });

    it('all interface methods execute without throwing exceptions', function () {
        // This test verifies that all methods can be called without exceptions
        expect(fn () => $this->kiro->name())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->displayName())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->systemDetectionConfig(Platform::Darwin))->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->projectDetectionConfig())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->mcpConfigPath())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->guidelinesPath())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->frontmatter())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->useAbsolutePathForMcp())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->getPhpPath())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->getArtisanPath())->not->toThrow(Exception::class);
    });
});
