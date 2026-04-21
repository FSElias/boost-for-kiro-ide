<?php

declare(strict_types=1);

use Jcf\BoostForKiro\BoostForKiroServiceProvider;

describe('ServiceProvider Registration Integration', function () {
    it('service provider can be instantiated without exceptions', function () {
        expect(fn () => new BoostForKiroServiceProvider(app()))->not->toThrow(Exception::class);
    });
});
