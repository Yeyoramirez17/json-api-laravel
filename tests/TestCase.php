<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Summary of TestCase
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MakesJsonApiRequests;

}
