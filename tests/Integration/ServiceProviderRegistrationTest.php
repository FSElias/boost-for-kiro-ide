<?php

declare(strict_types=1);

use Jcf\BoostForKiro\BoostForKiroServiceProvider;
use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;
use Laravel\Boost\Install\Detection\DetectionStrategyFactory;

describe('ServiceProvider Registration Integration', function () {
    it('boots without throwing exceptions', function () {
        expect(true)->toBeTrue();
    });

    it('registers kiro agent with Boost', function () {
        $agents = Boost::getAgents();

        expect($agents)
            ->toBeArray()
            ->toHaveKey('kiro')
            ->and($agents['kiro'])
            ->toBe(Kiro::class);
    });

    it('allows kiro agent to be resolved from container', function () {
        expect(app(Kiro::class))->toBeInstanceOf(Kiro::class);
    });

    it('service provider can be instantiated without exceptions', function () {
        expect(fn () => new BoostForKiroServiceProvider(app()))->not->toThrow(Exception::class);
    });

    it('resolves Kiro with DetectionStrategyFactory injected', function () {
        $kiro = app(Kiro::class);

        $reflection = new ReflectionClass($kiro);
        $property = $reflection->getProperty('strategyFactory');
        $property->setAccessible(true);

        expect($property->getValue($kiro))->toBeInstanceOf(DetectionStrategyFactory::class);
    });

    it('inherited detectInProject executes without errors', function () {
        $kiro = app(Kiro::class);
        $result = $kiro->detectInProject(sys_get_temp_dir());

        expect($result)->toBeBool();
    });
});
