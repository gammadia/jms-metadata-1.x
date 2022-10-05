<?php

declare(strict_types=1);

namespace Metadata;

trait SerializationHelper
{
    /**
     * @deprecated Use serializeToArray
     *
     * @return string
     */
    public function serialize()
    {
        return serialize($this->serializeToArray());
    }

    /**
     * @deprecated Use unserializeFromArray
     *
     * @param string $str
     *
     * @return void
     */
    public function unserialize($str)
    {
        /** @var mixed[] $array */
        $array = unserialize($str);

        $this->unserializeFromArray($array);
    }

    /**
     * @return mixed[]
     */
    public function __serialize(): array
    {
        return $this->serializeToArray();
    }

    /**
     * @param mixed[] $data
     */
    public function __unserialize(array $data): void
    {
        $this->unserializeFromArray($data);
    }
}
