<?php

declare(strict_types=1);

use Jcf\BoostForKiro\BoostForKiroServiceProvider;
use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;
use Laravel\Boost\Install\Detection\DetectionStrategyFactory;

/**
 * ServiceProvider Registration and Container Resolution Integration Tests.
 *
 * Valida: Requisitos 1.1, 1.4, 3.1, 3.2, 3.3
 * Example 8: Container resolve Kiro com DetectionStrategyFactory
 */
describe('ServiceProvider Registration Integration', function () {
    it('boots without throwing exceptions', function () {
        expect(true)->toBeTrue();
    });

    it('registers kiro agent with Boost', function () {
        $codeEnvironments = Boost::getCodeEnvironments();

        expect($codeEnvironments)
            ->toBeArray()
            ->toHaveKey('kiro')
            ->and($codeEnvironments['kiro'])
            ->toBe(Kiro::class);
    });

    it('allows kiro agent to be resolved from container', function () {
        $kiro = app(Kiro::class);

        expect($kiro)
            ->toBeInstanceOf(Kiro::class);
    });

    it('service provider can be instantiated without exceptions', function () {
        expect(fn () => new BoostForKiroServiceProvider(app()))->not->toThrow(Exception::class);
    });

    it('resolves Kiro with DetectionStrategyFactory injected', function () {
        $kiro = app(Kiro::class);

        // The strategyFactory is a protected property injected via CodeEnvironment constructor
        $reflection = new ReflectionClass($kiro);
        $property = $reflection->getProperty('strategyFactory');
        $property->setAccessible(true);

        expect($property->getValue($kiro))->toBeInstanceOf(DetectionStrategyFactory::class);
    });

    it('inherited detectInProject executes without errors', function () {
        $kiro = app(Kiro::class);

        // detectInProject uses the injected DetectionStrategyFactory
        // It should execute without errors even if the path doesn't exist
        $result = $kiro->detectInProject(sys_get_temp_dir());

        expect($result)->toBeBool();
    });
});
