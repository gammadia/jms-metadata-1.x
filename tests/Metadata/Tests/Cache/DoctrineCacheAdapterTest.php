<?php

namespace Metadata\Tests\Cache;

use Metadata\ClassMetadata;
use Metadata\Cache\DoctrineCacheAdapter;
use Doctrine\Common\Cache\ArrayCache;

/**
 * @requires PHP 5.4
 */
class DoctrineCacheAdapterTest extends \PHPUnit\Framework\TestCase
{
    public function setUp(): void
    {
        if (!interface_exists('Doctrine\Common\Cache\Cache')) {
            $this->markTestSkipped('Doctrine\Common is not installed.');
        }
    }

    public function testLoadEvictPutClassMetadataFromInCache()
    {
        $cache = new DoctrineCacheAdapter('metadata-test', new ArrayCache());

        $this->assertNull($cache->loadClassMetadataFromCache($refl = new \ReflectionClass('Metadata\Tests\Fixtures\TestObject')));
        $cache->putClassMetadataInCache($metadata = new ClassMetadata('Metadata\Tests\Fixtures\TestObject'));

        $this->assertEquals($metadata, $cache->loadClassMetadataFromCache($refl));

        $cache->evictClassMetadataFromCache($refl);
        $this->assertNull($cache->loadClassMetadataFromCache($refl));
    }
}
