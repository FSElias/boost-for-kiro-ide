<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;

/**
 * Example 8: Frontmatter Retorna False
 *
 * Valida: Requisitos 8.1
 *
 * Testa que frontmatter() retorna false
 */
describe('Frontmatter Configuration Integration', function () {
    beforeEach(function () {
        $this->kiro = app(Kiro::class);
    });

    it('returns false for frontmatter', function () {
        $result = $this->kiro->frontmatter();

        expect($result)
            ->toBeFalse()
            ->and($result)
            ->toBeBool();
    });

    it('frontmatter is consistently false across multiple calls', function () {
        $result1 = $this->kiro->frontmatter();
        $result2 = $this->kiro->frontmatter();
        $result3 = $this->kiro->frontmatter();

        expect($result1)
            ->toBe($result2)
            ->toBe($result3)
            ->toBeFalse();
    });

    it('frontmatter returns boolean type', function () {
        $result = $this->kiro->frontmatter();

        expect($result)
            ->toBeBool()
            ->not->toBeNull()
            ->not->toBeString()
            ->not->toBeInt();
    });
});
