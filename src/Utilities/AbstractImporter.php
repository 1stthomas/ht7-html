<?php

namespace Ht7\Html\Utilities;

use \Ht7\Html\Tag;

abstract class AbstractImporter
{

    protected static $instances;

    /**
     * Get the singleton of the present importer class.
     *
     * @return type
     * @throws \BadMethodCallException
     */
    public static function getInstance(string $class = '')
    {
        if (empty($class) || $class === AbstractImporter::class) {
            if (static::class === AbstractImporter::class || $class === AbstractImporter::class) {
                $e = 'Can not instanciate an abstract class.';

                throw new \BadMethodCallException($e);
            }

            $class = static::class;
        }

        if (empty(static::$instances[$class])) {
            static::$instances[$class] = new $class();
        }

        return static::$instances[$class];
    }

    /**
     * Set the singleton of a specific importer class.
     *
     * @param   AbstractImporter    $importer   The importer instance to set.
     * @param   string              $key        Optional key. If empty, the full
     *                                          namespaced class name will be used.
     */
    public static function setInstance(AbstractImporter $importer, string $key = '')
    {
        if ($key === '') {
            $key = get_class($importer);
        }

        static::$instances[$key] = $importer;
    }

    /**
     * Get a Tag representation of the html in the present string.
     *
     * This function uses internally the DOMDocument and its anchestors to gain
     * a valid HTML DOM. This Dom is parsed to gain the Tag tree.
     *
     * @param   mixed   $input              Object, array or string with html definitions.
     *                                      See implementations of this class.
     * @return  Tag|null                    A Tag tree. If empty a new Tag instance
     *                                      will be created.
     * @throws  \InvalidArgumentException
     */
    abstract public function import($input);
}
