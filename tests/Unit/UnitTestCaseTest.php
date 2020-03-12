<?php

namespace Comsave\SymfonyTestingBase\Tests;

use Comsave\SymfonyTestingBase\UnitTestCase;

class UnitTestCaseTest extends UnitTestCase
{
    public function testTrue(): void
    {
        $this->assertTrue(true);
    }
}