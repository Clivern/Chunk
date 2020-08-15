<?php

/*
 * This file is part of Chunk - Asynchronous Task Queue Based on Distributed Message Passing for PHP
 * (c) Clivern <hello@clivern.com>
 */

namespace Tests\Core;

use Clivern\Chunk\Core\Config;
use PHPUnit\Framework\TestCase;

/**
 * Config Class Test.
 */
class ConfigTest extends TestCase
{
    public function testConfig()
    {
        $config = new Config();
        $this->assertTrue($config instanceof Config);
        $config->set('key', 'value');
        $this->assertSame('value', $config->get('key', null));
        $this->assertTrue($config->exists('key'));
        $this->assertFalse($config->exists('not_found'));
    }
}
