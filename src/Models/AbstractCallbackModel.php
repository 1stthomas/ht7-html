<?php

namespace Ht7\Html\Models;

/**
 * Base model class for the callback node.
 */
abstract class AbstractCallbackModel implements \JsonSerializable
{

    /**
     * The callback parameters.
     *
     * @var array
     */
    protected $parameters;

    /**
     * Create an instance of the base callback model class.
     *
     * @param   array   $parameters     Assoc array.
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Get the callback parameters.
     *
     * @return  array                   Assoc array.
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    public function jsonSerialize()
    {
        return [
            'parameters' => $this->getParameters(),
            'type' => 'callback',
        ];
    }

}
