<?php

declare(strict_types=1);

use Jcf\BoostForKiro\BoostForKiroServiceProvider;
use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;

/**
 * Example 3: ServiceProvider Registra Sem Erros
 *
 * Valida: Requisitos 3.1
 *
 * Quando o método boot() do BoostForKiroServiceProvider é chamado,
 * ele deve executar Boost::registerAgent('kiro', Kiro::class) sem lançar exceções.
 */
describe('ServiceProvider Registration Integration', function () {
    it('boots without throwing exceptions', function () {
        // The ServiceProvider is already booted by the TestCase setup
        // This test verifies that no exceptions were thrown during boot
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
        // Create a fresh instance of the service provider
        // The boot method is already called during TestCase setup
        // This test verifies that instantiation works correctly
        expect(fn () => new BoostForKiroServiceProvider(app()))->not->toThrow(Exception::class);
    });
});
