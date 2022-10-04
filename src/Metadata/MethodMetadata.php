<?php

namespace Metadata;

/**
 * Base class for method metadata.
 *
 * This class is intended to be extended to add your application specific
 * properties, and flags.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class MethodMetadata implements \Serializable
{
    use SerializationHelper;

    public $class;
    public $name;
    public $reflection;

    public function __construct($class, $name)
    {
        $this->class = $class;
        $this->name = $name;

        $this->reflection = new \ReflectionMethod($class, $name);
        $this->reflection->setAccessible(true);
    }

    /**
     * @param object $obj
     * @param array  $args
     *
     * @return mixed
     */
    public function invoke($obj, array $args = array())
    {
        return $this->reflection->invokeArgs($obj, $args);
    }

    /**
     * @return mixed[]
     */
    protected function serializeToArray(): array
    {
        return [$this->class, $this->name];
    }

    /**
     * @param mixed[] $data
     */
    protected function unserializeFromArray(array $data): void
    {
        [$this->class, $this->name] = $data;

        $this->reflection = new \ReflectionMethod($this->class, $this->name);
        $this->reflection->setAccessible(true);
    }
}
