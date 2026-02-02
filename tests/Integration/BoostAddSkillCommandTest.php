<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;

/**
 * Tests for the boost:add-skill command integration with Kiro IDE.
 *
 * These tests verify that:
 * - Kiro agent is properly registered for skill installation
 * - The package provides all necessary interfaces for skill support
 * - Skills can be installed when Kiro is the registered agent
 *
 * Validates: Requirements 6.1, 6.2
 *
 * Note: These tests verify the components that boost:add-skill uses.
 * Manual testing of the actual command should be done in a real Laravel project.
 * See tests/Integration/MANUAL_TESTING_GUIDE.md for manual testing instructions.
 */
describe('Boost Add Skill Command Integration', function () {
    it('kiro agent is registered and available for skill installation', function () {
        $agents = Boost::getAgents();

        expect($agents)->toHaveKey('kiro')
            ->and($agents['kiro'])->toBe(Kiro::class);
    });

    it('kiro agent can be instantiated for skill operations', function () {
        $kiro = app(Kiro::class);

        expect($kiro)->toBeInstanceOf(Kiro::class)
            ->and($kiro->name())->toBe('kiro')
            ->and($kiro->displayName())->toBe('Kiro');
    });

    it('kiro provides guidelines path for skill integration', function () {
        $kiro = app(Kiro::class);

        $guidelinesPath = $kiro->guidelinesPath();

        expect($guidelinesPath)->toBe('.kiro/steering/laravel-boost.md')
            ->and($guidelinesPath)->toContain('.kiro')
            ->and($guidelinesPath)->toContain('steering');
    });

    it('kiro implements SupportsGuidelines interface for skills', function () {
        $kiro = app(Kiro::class);

        expect($kiro)->toBeInstanceOf(\Laravel\Boost\Contracts\SupportsGuidelines::class);
    });

    it('kiro implements SupportsMcp interface for skill communication', function () {
        $kiro = app(Kiro::class);

        expect($kiro)->toBeInstanceOf(\Laravel\Boost\Contracts\SupportsMcp::class);
    });

    it('kiro provides mcp config path for skill mcp servers', function () {
        $kiro = app(Kiro::class);

        $mcpPath = $kiro->mcpConfigPath();

        expect($mcpPath)->toBe('.kiro/settings/mcp.json')
            ->and($mcpPath)->toContain('.kiro')
            ->and($mcpPath)->toContain('settings');
    });

    it('kiro configuration supports skill installation workflow', function () {
        $kiro = app(Kiro::class);

        // Verify all required methods for skill installation are available
        expect($kiro->name())->toBe('kiro')
            ->and($kiro->displayName())->toBe('Kiro')
            ->and($kiro->guidelinesPath())->toBeString()
            ->and($kiro->mcpConfigPath())->toBeString()
            ->and($kiro->frontmatter())->toBeBool();
    });

    it('kiro agent registration persists across container resolutions', function () {
        // First resolution
        $agents1 = Boost::getAgents();
        $kiro1 = app(Kiro::class);

        // Second resolution
        $agents2 = Boost::getAgents();
        $kiro2 = app(Kiro::class);

        expect($agents1)->toEqual($agents2)
            ->and($kiro1->name())->toBe($kiro2->name())
            ->and($kiro1->displayName())->toBe($kiro2->displayName());
    });
});
