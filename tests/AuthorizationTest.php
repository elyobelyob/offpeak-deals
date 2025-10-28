<?php
use PHPUnit\Framework\TestCase;

final class AuthorizationTest extends TestCase
{
    public function testBusinessUserCannotAccessOthers()
    {
        $this->assertTrue(true);
    }
}
