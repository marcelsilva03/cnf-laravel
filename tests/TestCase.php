<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CustomCreatesApplication;

abstract class TestCase extends BaseTestCase
{
    use CustomCreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup if needed
    }
}
