<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Contracts\Agent;
use Laravel\Boost\Contracts\McpClient;
use Laravel\Boost\Install\CodeEnvironment\CodeEnvironment;
use Laravel\Boost\Install\Enums\McpInstallationStrategy;
use Laravel\Boost\Install\Enums\Platform;

/**
 * Example 4: Kiro implementa hierarquia de classes correta
 * Valida: Requisitos 2.1, 2.2, 2.3
 */
it('implements the correct class hierarchy', function () {
    $kiro = app(Kiro::class);

    expect($kiro)
        ->toBeInstanceOf(CodeEnvironment::class)
        ->toBeInstanceOf(Agent::class)
        ->toBeInstanceOf(McpClient::class);
});

/**
 * Example 5: Métodos da classe Kiro retornam valores corretos
 * Valida: Requisitos 2.4, 2.5, 2.6, 2.7, 2.8, 2.9
 */
it('returns correct values from all methods', function () {
    $kiro = app(Kiro::class);

    expect($kiro->name())->toBe('kiro')
        ->and($kiro->displayName())->toBe('Kiro')
        ->and($kiro->mcpConfigPath())->toBe('.kiro/settings/mcp.json')
        ->and($kiro->guidelinesPath())->toBe('.kiro/steering/laravel-boost.md')
        ->and($kiro->frontmatter())->toBeFalse()
        ->and($kiro->mcpInstallationStrategy())->toBe(McpInstallationStrategy::FILE);
});

/**
 * Example 5 (cont): mcpConfigPath retorna string não-nullable
 * Valida: Requisito 2.5
 */
it('returns mcpConfigPath as non-nullable string', function () {
    $kiro = app(Kiro::class);
    $result = $kiro->mcpConfigPath();

    expect($result)->toBeString()->not->toBeNull();
});

/**
 * Example 6: Configurações de detecção por plataforma estão corretas
 * Valida: Requisitos 4.1, 4.2, 4.3, 4.4
 */
it('provides correct system detection config for darwin', function () {
    $kiro = app(Kiro::class);
    $config = $kiro->systemDetectionConfig(Platform::Darwin);

    expect($config)
        ->toHaveKey('paths')
        ->and($config['paths'])
        ->toContain('/Applications/Kiro.app');
});

it('provides correct system detection config for linux', function () {
    $kiro = app(Kiro::class);
    $config = $kiro->systemDetectionConfig(Platform::Linux);

    expect($config)
        ->toHaveKey('paths')
        ->and($config['paths'])
        ->toContain('/opt/kiro')
        ->toContain('/usr/local/bin/kiro')
        ->toContain('~/.local/bin/kiro');
});

it('provides correct system detection config for windows', function () {
    $kiro = app(Kiro::class);
    $config = $kiro->systemDetectionConfig(Platform::Windows);

    expect($config)
        ->toHaveKey('paths')
        ->and($config['paths'])
        ->toContain('%ProgramFiles%\\Kiro')
        ->toContain('%LOCALAPPDATA%\\Programs\\Kiro');
});

it('provides correct project detection config', function () {
    $kiro = app(Kiro::class);
    $config = $kiro->projectDetectionConfig();

    expect($config)
        ->toHaveKey('paths')
        ->and($config['paths'])
        ->toContain('.kiro');
});

/**
 * Example 7: Instalação MCP usa estratégia FILE
 * Valida: Requisitos 5.1, 5.3
 */
it('uses FILE mcp installation strategy', function () {
    $kiro = app(Kiro::class);

    expect($kiro->mcpInstallationStrategy())->toBe(McpInstallationStrategy::FILE)
        ->and($kiro->mcpConfigPath())->toBe('.kiro/settings/mcp.json')
        ->and($kiro->mcpConfigKey())->toBe('mcpServers');
});
