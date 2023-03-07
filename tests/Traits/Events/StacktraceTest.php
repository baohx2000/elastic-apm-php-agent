<?php

namespace Nipwaayoni\Tests\Traits\Events;

use Nipwaayoni\Traits\Events\Stacktrace;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Nipwaayoni\Tests\PHPUnitUtils;

/**
 * Test Case for @see \Nipwaayoni\Traits\Events\Stacktrace
 */
final class StacktraceTest extends TestCase
{
    /** @var Stacktrace|MockObject */
    private $stacktraceMock;

    protected function setUp(): void
    {
        $this->stacktraceMock = $this->getMockForTrait(Stacktrace::class);
    }

    protected function tearDown(): void
    {
        $this->stacktraceMock = null;
    }

    /**
     * @covers \Nipwaayoni\Traits\Events\Stacktrace::getDebugBacktrace
     */
    public function testEntry()
    {
        $n = rand(4, 7);
        $result = PHPUnitUtils::callMethod($this->stacktraceMock, 'getDebugBacktrace', [$n]);

        // Ensure the first elem is not present (self)
        $this->assertEquals(count($result), ($n - 1));

        $this->assertArrayHasKey('abs_path', $result[0]);
        $this->assertArrayHasKey('filename', $result[0]);
        $this->assertArrayHasKey('function', $result[0]);
        $this->assertArrayHasKey('lineno', $result[0]);
        $this->assertArrayHasKey('module', $result[0]);
        $this->assertArrayHasKey('vars', $result[0]);
        $this->assertArrayHasKey(1, $result[0]['vars']);

        $this->assertStringEndsWith('tests/PHPUnitUtils.php', $result[0]['abs_path']);
        $this->assertEquals('PHPUnitUtils.php', $result[0]['filename']);
        $this->assertEquals('invokeArgs', $result[0]['function']);
        $this->assertEquals(15, $result[0]['lineno']);
        $this->assertEquals('ReflectionMethod', $result[0]['module']);
        $this->assertEquals($n, $result[0]['vars'][1][0]);

        $this->assertStringEndsWith('tests/Traits/Events/StacktraceTest.php', $result[1]['abs_path']);
        $this->assertEquals('StacktraceTest.php', $result[1]['filename']);
        $this->assertEquals('callMethod', $result[1]['function']);
        $this->assertEquals(34, $result[1]['lineno']);
        $this->assertEquals('Nipwaayoni\Tests\PHPUnitUtils', $result[1]['module']);
    }
}
