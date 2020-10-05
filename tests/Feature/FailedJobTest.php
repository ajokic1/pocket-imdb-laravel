<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FailedJobTest extends TestCase
{
    public function testFailedJob()
    {
        dispatch(function () {
            throw new \Exception();
        });
    }
}
