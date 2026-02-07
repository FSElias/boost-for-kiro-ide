<?php

declare(strict_types=1);

use Jcf\BoostForKiro\CodeEnvironment\Kiro;
use Laravel\Boost\Boost;

// Example 1: ServiceProvider registra Kiro via Facade no boot()
it('registers kiro code environment via Boost facade during boot', function () {
    $codeEnvironments = Boost::getCodeEnvironments();

    expect($codeEnvironments)
        ->toHaveKey('kiro')
        ->and($codeEnvironments['kiro'])
        ->toBe(Kiro::class);
});

// Example 2: Registro não acontece no register()
it('does not register kiro during register phase', function () {
    // The ServiceProvider's register() method should be empty.
    // We verify this indirectly: the source code must not contain
    // any registration logic in register().
    $source = file_get_contents(__DIR__.'/../../src/BoostForKiroServiceProvider.php');
    $registerMatch = preg_match('/function register\(\).*?\{(.*?)\}/s', $source, $matches);

    expect($registerMatch)->toBe(1);

    $registerBody = trim($matches[1]);
    // The body should only contain a comment or be empty
    $withoutComments = trim(preg_replace('#//.*$#m', '', $registerBody));
    expect($withoutComments)->toBe('');
});

// Example 3: ServiceProvider trata ausência do Boost graciosamente
it('handles missing Boost class gracefully', function () {
    // The boot() method guards with class_exists(Boost::class),
    // so if Boost were absent, no exception would be thrown.
    // We verify the guard exists in the source code.
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
        ->not->toContain('use Laravel\Boost\BoostManager');
});
