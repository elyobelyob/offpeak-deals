<?php
use PHPUnit\Framework\TestCase;

final class DatabaseConnectionTest extends TestCase {
    public function testCanConnectToDatabase() {
        $conn = new mysqli(getenv('DB_HOST') ?: 'db', getenv('DB_USER') ?: 'appuser', getenv('DB_PASS') ?: 'secretpass', getenv('DB_NAME') ?: 'offpeak');
        $this->assertFalse($conn->connect_errno);
        $conn->close();
    }
}
