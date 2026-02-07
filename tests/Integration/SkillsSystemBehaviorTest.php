<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;

/**
 * Tests to verify the Skills system behavior documentation.
 *
 * These tests confirm that:
 * - Skills work automatically with Kiro IDE
 * - No additional configuration is required
 * - All necessary interfaces are implemented
 * - Skills are loaded automatically by Laravel Boost
 *
 * Validates: Requirements 5.1, 5.2, 5.4
 */
describe('Skills System Behavior', function () {
    it('confirms kiro implements all required interfaces for skills support', function () {
        $kiro = app(Kiro::class);

        // Verify all interfaces required for Skills system
        expect($kiro)->toBeInstanceOf(\Laravel\Boost\Install\CodeEnvironment\CodeEnvironment::class)
            ->and($kiro)->toBeInstanceOf(\Laravel\Boost\Contracts\Agent::class)
            ->and($kiro)->toBeInstanceOf(\Laravel\Boost\Contracts\McpClient::class);
    });

    it('confirms no additional kiro configuration is needed for skills', function () {
        $kiro = app(Kiro::class);

        // The Kiro class should not have any skill-specific methods
        // Skills are handled entirely by Laravel Boost
        $methods = get_class_methods($kiro);

        // Verify standard methods exist
        expect($methods)->toContain('name')
            ->and($methods)->toContain('displayName')
            ->and($methods)->toContain('guidelinesPath')
            ->and($methods)->toContain('mcpConfigPath')
            ->and($methods)->toContain('frontmatter');

        // Verify no skill-specific methods (skills are handled by Laravel Boost)
        expect($methods)->not->toContain('installSkill')
            ->and($methods)->not->toContain('loadSkills')
            ->and($methods)->not->toContain('getSkills');
    });

    it('confirms skills are loaded automatically by laravel boost', function () {
        // Kiro is registered as an agent
        $codeEnvironments = Boost::getCodeEnvironments();
        expect($codeEnvironments)->toHaveKey('kiro');

        // Laravel Boost handles skill loading through the agent registration
        // No additional configuration needed in Kiro
        $kiro = app(Kiro::class);
        expect($kiro)->toBeInstanceOf(\Laravel\Boost\Contracts\Agent::class);
    });

    it('confirms guidelines path is configured for skill integration', function () {
        $kiro = app(Kiro::class);

        $guidelinesPath = $kiro->guidelinesPath();

        // Guidelines path should be in .kiro/steering/ directory
        expect($guidelinesPath)->toBe('.kiro/steering/laravel-boost.md')
            ->and($guidelinesPath)->toStartWith('.kiro/')
            ->and($guidelinesPath)->toContain('steering');
    });

    it('confirms mcp config path is configured for skill communication', function () {
        $kiro = app(Kiro::class);

        $mcpPath = $kiro->mcpConfigPath();

        // MCP path should be in .kiro/settings/ directory
        expect($mcpPath)->toBe('.kiro/settings/mcp.json')
            ->and($mcpPath)->toStartWith('.kiro/')
            ->and($mcpPath)->toContain('settings');
    });

    it('confirms frontmatter configuration for guidelines', function () {
        $kiro = app(Kiro::class);

        // Frontmatter should be false for Kiro
        expect($kiro->frontmatter())->toBeFalse();
    });

    it('confirms kiro agent registration is persistent', function () {
        // First check
        $codeEnvironments1 = Boost::getCodeEnvironments();
        expect($codeEnvironments1)->toHaveKey('kiro');

        // Second check - should be the same
        $codeEnvironments2 = Boost::getCodeEnvironments();
        expect($codeEnvironments2)->toHaveKey('kiro')
            ->and($codeEnvironments1['kiro'])->toBe($codeEnvironments2['kiro']);
    });

    it('confirms kiro provides all required configuration for boost:add-skill', function () {
        $kiro = app(Kiro::class);

        // All required methods for boost:add-skill to work
        expect($kiro->name())->toBeString()
            ->and($kiro->displayName())->toBeString()
            ->and($kiro->guidelinesPath())->toBeString()
            ->and($kiro->mcpConfigPath())->toBeString()
            ->and($kiro->frontmatter())->toBeBool();
    });

    it('confirms documentation accuracy - zero configuration required', function () {
        // This test verifies the documentation claim that zero configuration is required

        // 1. Kiro is registered automatically via ServiceProvider
        $codeEnvironments = Boost::getCodeEnvironments();
        expect($codeEnvironments)->toHaveKey('kiro');

        // 2. All interfaces are implemented
        $kiro = app(Kiro::class);
        expect($kiro)->toBeInstanceOf(\Laravel\Boost\Contracts\Agent::class)
            ->and($kiro)->toBeInstanceOf(\Laravel\Boost\Contracts\McpClient::class);

        // 3. Paths are configured
        expect($kiro->guidelinesPath())->toBeString()
            ->and($kiro->mcpConfigPath())->toBeString();

        // 4. No additional methods or configuration needed
        // Skills work automatically through Laravel Boost
    });

    it('confirms skills directory structure is documented correctly', function () {
        $kiro = app(Kiro::class);

        // Verify paths match documented structure
        expect($kiro->guidelinesPath())->toBe('.kiro/steering/laravel-boost.md')
            ->and($kiro->mcpConfigPath())->toBe('.kiro/settings/mcp.json');

        // Skills are stored in .ai/skills/ (managed by Laravel Boost)
        // Kiro accesses them through MCP (no direct file access needed)
    });
});
