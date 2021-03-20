<?php

declare(strict_types=1);

use Clivern\CodingStandards\Rules;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;


$fileHeaderComment = <<<COMMENT
This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
(c) Clivern <hello@clivern.com>
COMMENT;

$finder = Finder::create()
    ->name('.php_cs.dist')
    ->in(__DIR__)
    ->exclude('vendor')
    ->exclude('examples');

$overrides = [
    'declare_strict_types' => true,
];

return (new Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules(Rules::PHP72($fileHeaderComment, $overrides));
