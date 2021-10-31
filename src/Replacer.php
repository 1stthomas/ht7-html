<?php

namespace Ht7\Html;

use \Ht7\Html\Node;
use \Ht7\Html\Callback;

/**
 * The replacer node can
 */
class Replacer extends Node
{

    /**
     * The callback node or its definition if not already initialised.
     *
     * @var array|Callback
     */
    protected $callback;

    /**
     *
     * @var string
     */
    protected $id;

    /**
     * The registry namespace. - Not in use atm.
     *
     * @var string
     */
    protected $namespace;

    public function __construct(array $parameters)
    {
        if (empty($parameters['id'])) {
            throw new \InvalidArgumentException('Missing id parameter.');
        }

        $this->setId($parameters['id']);
        $this->setCallback(isset($parameters['callback']) ? $parameters['callback'] : []);
        $this->setNamespace(isset($parameters['namespace']) ? $parameters['namespace'] : 'ht7/replacer');

//        $this->register();
    }

    public function __toString()
    {
        return (string) $this->getCallback();
    }

    public function getCallback()
    {
        if (is_array($this->callback)) {
            $this->callback = new Callback($this->callback);
        }

        return $this->callback;
    }

    /**
     * Get the id of the present replacer node.
     *
     * @return  string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the namespace used by the registry.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->getCallback();
    }

    public function setCallback($callback = [])
    {
        if (!is_array($callback) && !(is_object($callback) && !($callback instanceof Callback))) {
            $e = 'Unsupported callback parameter '
                    . (is_object($callback) ? get_class($callback) : gettype($callback));

            throw new \InvalidArgumentException($e);
        }

        if (empty($callback)) {
            $callback['method'] = '';

            $e = 'Getting the parameters from a registry in case nothing is defined is not supported.';

            throw new \InvalidArgumentException($e);
        }

        if (is_array($callback)) {
            $callback['parameters'] = empty($callback['parameters']) ? [] : $callback['parameters'];
            $callback['parameters']['id'] = $this->getId();
        }

        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Set the replacer node id.
     *
     * @param   string  $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * Set the replacer namespace used by the registry.
     *
     * @param   string  $namespace
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

}
