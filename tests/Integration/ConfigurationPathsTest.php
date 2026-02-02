<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;

/**
 * Example 5: boost:install Cria Arquivos nos Paths Corretos
 *
 * Valida: Requisitos 4.1, 4.2, 4.3
 *
 * Testa que mcpConfigPath() retorna ".kiro/settings/mcp.json"
 * Testa que guidelinesPath() retorna ".kiro/steering/laravel-boost.md"
 */
describe('Configuration Paths Integration', function () {
    beforeEach(function () {
        $this->kiro = app(Kiro::class);
    });

    it('returns correct mcp config path', function () {
        $path = $this->kiro->mcpConfigPath();

        expect($path)
            ->toBe('.kiro/settings/mcp.json')
            ->and($path)
            ->toContain('.kiro')
            ->toContain('settings')
            ->toContain('mcp.json');
    });

    it('returns correct guidelines path', function () {
        $path = $this->kiro->guidelinesPath();

        expect($path)
            ->toBe('.kiro/steering/laravel-boost.md')
            ->and($path)
            ->toContain('.kiro')
            ->toContain('steering')
            ->toContain('laravel-boost.md');
    });

    it('mcp config path is relative and starts with .kiro', function () {
        $path = $this->kiro->mcpConfigPath();

        expect($path)
            ->toStartWith('.kiro/')
            ->not->toStartWith('/')
            ->not->toContain('\\');
    });

    it('guidelines path is relative and starts with .kiro', function () {
        $path = $this->kiro->guidelinesPath();

        expect($path)
            ->toStartWith('.kiro/')
            ->not->toStartWith('/')
            ->not->toContain('\\');
    });

    it('paths are consistent across multiple calls', function () {
        $mcpPath1 = $this->kiro->mcpConfigPath();
        $mcpPath2 = $this->kiro->mcpConfigPath();
        $guidelinesPath1 = $this->kiro->guidelinesPath();
        $guidelinesPath2 = $this->kiro->guidelinesPath();

        expect($mcpPath1)
            ->toBe($mcpPath2)
            ->and($guidelinesPath1)
            ->toBe($guidelinesPath2);
    });
});
