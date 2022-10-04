<?php

namespace Metadata\Tests\Driver;

use Metadata\ClassMetadata;

/**
 * @author Jordan Stout <j@jrdn.org>
 */
class AbstractFileDriverTest extends \PHPUnit\Framework\TestCase
{
    private static $extension = 'jms_metadata.yml';

    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $locator;

    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $driver;

    public function setUp(): void
    {
        $this->locator = $this->createMock('Metadata\Driver\FileLocator');
        $this->driver = $this->getMockBuilder('Metadata\Driver\AbstractFileDriver')
            ->setConstructorArgs(array($this->locator))
            ->getMockForAbstractClass();

        $this->driver->expects($this->any())->method('getExtension')->will($this->returnValue(self::$extension));
    }

    public function testLoadMetadataForClass()
    {
        $class = new \ReflectionClass('\stdClass');
        $this->locator
            ->expects($this->once())
            ->method('findFileForClass')
            ->with($class, self::$extension)
            ->will($this->returnValue('Some\Path'));

        $this->driver
            ->expects($this->once())
            ->method('loadMetadataFromFile')
            ->with($class, 'Some\Path')
            ->will($this->returnValue($metadata = new ClassMetadata('\stdClass')));

        $this->assertSame($metadata, $this->driver->loadMetadataForClass($class));
    }

    public function testLoadMetadataForClassWillReturnNull()
    {
        $class = new \ReflectionClass('\stdClass');
        $this->locator
            ->expects($this->once())
            ->method('findFileForClass')
            ->with($class, self::$extension)
            ->will($this->returnValue(null));

        $this->assertSame(null, $this->driver->loadMetadataForClass($class));
    }

    public function testGetAllClassNames()
    {
        $class = new \ReflectionClass('\stdClass');
        $this->locator
            ->expects($this->once())
            ->method('findAllClasses')
            ->with(self::$extension)
            ->will($this->returnValue(array('\stdClass')));

        $this->assertSame(array('\stdClass'), $this->driver->getAllClassNames($class));
    }
}
