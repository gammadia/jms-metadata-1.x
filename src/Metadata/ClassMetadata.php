<?php

namespace Metadata;

/**
 * Base class for class metadata.
 *
 * This class is intended to be extended to add your own application specific
 * properties, and flags.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class ClassMetadata implements \Serializable
{
    use SerializationHelper;

    public $name;
    public $reflection;
    public $methodMetadata = array();
    public $propertyMetadata = array();
    public $fileResources = array();
    public $createdAt;

    public function __construct($name)
    {
        $this->name = $name;

        $this->reflection = new \ReflectionClass($name);
        $this->createdAt = time();
    }

    public function addMethodMetadata(MethodMetadata $metadata)
    {
        $this->methodMetadata[$metadata->name] = $metadata;
    }

    public function addPropertyMetadata(PropertyMetadata $metadata)
    {
        $this->propertyMetadata[$metadata->name] = $metadata;
    }

    public function isFresh($timestamp = null)
    {
        if (null === $timestamp) {
            $timestamp = $this->createdAt;
        }

        foreach ($this->fileResources as $filepath) {
            if (!file_exists($filepath)) {
                return false;
            }

            if ($timestamp < filemtime($filepath)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return mixed[]
     */
    protected function serializeToArray(): array
    {
        return [
            $this->name,
            $this->methodMetadata,
            $this->propertyMetadata,
            $this->fileResources,
            $this->createdAt,
        ];
    }

    /**
     * @param mixed[] $data
     */
    protected function unserializeFromArray(array $data): void
    {
        [
            $this->name,
            $this->methodMetadata,
            $this->propertyMetadata,
            $this->fileResources,
            $this->createdAt
        ] = $data;

        $this->reflection = new \ReflectionClass($this->name);
    }
}
