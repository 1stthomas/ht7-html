<?php

namespace Ht7\Html\Utilities;

use \Ht7\Html\Tag;

abstract class AbstractImporter
{

    protected static $instances;

    /**
     *
     * @return AbstractImporter
     */
    public static function getInstance()
    {
        if (static::class === AbstractImporter::class) {
            $e = 'Can not instanciate an abstract class.';

            throw new \BadMethodCallException($e);
        }

        if (empty(static::$instances[static::class])) {
            static::$instances[static::class] = new static();
        }

        return static::$instances[static::class];
    }

    /**
     * Get a Tag representation of the html in the present string.
     *
     * This function uses internally the DOMDocument and its anchestors to gain
     * a valid HTML DOM. This Dom is parsed to gain the Tag tree.
     *
     * @param   mixed   $source             Object or string with html definitions.
     *                                      See implementations of this class.
     * @return  Tag|null                    A Tag tree. If empty a new Tag instance
     *                                      will be created.
     * @throws  \InvalidArgumentException
     */
    abstract public function read($source, Tag $tag = null);
}
