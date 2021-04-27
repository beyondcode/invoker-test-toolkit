<?php declare(strict_types=1);

// scoper.inc.php
use Isolated\Symfony\Component\Finder\Finder;

return [
    'prefix' => null,                       // string|null
    'finders' => [],                        // Finder[]
    'patchers' => [],                       // callable[]
    'files-whitelist' => [
        'vendor/symfony/polyfill-php80/bootstrap.php',
    ],
    'whitelist' => [
        'Carbon\*',
        'Illuminate\*',
        'Doctrine\DBAL\*',
        'Symfony\Polyfill\*'
    ],
    'whitelist-global-constants' => true,
    'whitelist-global-classes' => true,
    'whitelist-global-functions' => true,
];