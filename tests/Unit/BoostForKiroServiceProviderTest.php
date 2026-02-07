<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;

// Example 1: ServiceProvider registra Kiro via Facade no boot()
it('registers kiro agent via Boost facade during boot', function () {
    $agents = Boost::getAgents();

    expect($agents)
        ->toHaveKey('kiro')
        ->and($agents['kiro'])
        ->toBe(Kiro::class);
});

// Example 2: Registro não acontece no register()
it('does not register kiro during register phase', function () {
    $source = file_get_contents(__DIR__.'/../../src/BoostForKiroServiceProvider.php');
    $registerMatch = preg_match('/function register\(\).*?\{(.*?)\}/s', $source, $matches);

    expect($registerMatch)->toBe(1);

    $registerBody = trim($matches[1]);
    $withoutComments = trim(preg_replace('#//.*$#m', '', $registerBody));
    expect($withoutComments)->toBe('');
});

// Example 3: ServiceProvider trata ausência do Boost graciosamente
it('handles missing Boost class gracefully', function () {
    $source = file_get_contents(__DIR__.'/../../src/BoostForKiroServiceProvider.php');

    expect($source)->toContain('class_exists(Boost::class)');
});

// Example 9: Código do ServiceProvider está limpo
it('does not contain legacy registration patterns', function () {
    $source = file_get_contents(__DIR__.'/../../src/BoostForKiroServiceProvider.php');

    expect($source)
        ->not->toContain('$this->app->booted')
        ->not->toContain('$this->app->make(BoostManager')
        ->not->toContain('try {')
        ->not->toContain('use Laravel\Boost\BoostManager')
        ->not->toContain('registerCodeEnvironment');
});
