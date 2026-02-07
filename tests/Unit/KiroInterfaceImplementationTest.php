<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Install\Enums\Platform;

describe('Kiro interface implementation', function () {
    beforeEach(function () {
        $this->kiro = app(Kiro::class);
    });

    it('implements name method correctly', function () {
        expect($this->kiro->name())
            ->toBeString()
            ->not->toBeEmpty();
    });

    it('implements displayName method correctly', function () {
        expect($this->kiro->displayName())
            ->toBeString()
            ->not->toBeEmpty();
    });

    it('implements systemDetectionConfig method for all platforms', function () {
        foreach ([Platform::Darwin, Platform::Linux, Platform::Windows] as $platform) {
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
        expect($this->kiro->mcpConfigPath())
            ->toBeString()
            ->not->toBeEmpty()
            ->toContain('.kiro');
    });

    it('implements guidelinesPath method correctly', function () {
        expect($this->kiro->guidelinesPath())
            ->toBeString()
            ->not->toBeEmpty()
            ->toContain('.kiro');
    });

    it('implements skillsPath method correctly', function () {
        expect($this->kiro->skillsPath())
            ->toBeString()
            ->not->toBeEmpty()
            ->toContain('.kiro');
    });

    it('implements frontmatter method correctly', function () {
        expect($this->kiro->frontmatter())->toBeBool();
    });

    it('implements useAbsolutePathForMcp method correctly', function () {
        expect($this->kiro->useAbsolutePathForMcp())->toBeBool();
    });

    it('implements getPhpPath method correctly', function () {
        expect($this->kiro->getPhpPath())->toBeString()->not->toBeEmpty();
        expect($this->kiro->getPhpPath(true))->toBeString()->not->toBeEmpty();
    });

    it('implements getArtisanPath method correctly', function () {
        expect($this->kiro->getArtisanPath())->toBeString()->not->toBeEmpty();
        expect($this->kiro->getArtisanPath(true))->toBeString()->not->toBeEmpty();
    });

    it('all interface methods execute without throwing exceptions', function () {
        expect(fn () => $this->kiro->name())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->displayName())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->systemDetectionConfig(Platform::Darwin))->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->projectDetectionConfig())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->mcpConfigPath())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->guidelinesPath())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->skillsPath())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->frontmatter())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->useAbsolutePathForMcp())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->getPhpPath())->not->toThrow(Exception::class);
        expect(fn () => $this->kiro->getArtisanPath())->not->toThrow(Exception::class);
    });
});
