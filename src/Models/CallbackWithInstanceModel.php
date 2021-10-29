<?php

namespace Ht7\Html\Models;

use \Ht7\Html\Models\CallbackWithMethodModel;

/**
 *
 */
class CallbackWithInstanceModel extends CallbackWithMethodModel
{

    /**
     * The object on which the callback will be called.
     *
     * @var mixed
     */
    protected $instance;

    /**
     * {@inheritdoc}
     *
     * @param   mixed   $instance       The instance on which the callback will
     *                                  be called.
     */
    public function __construct($instance, $method, array $parameters = [])
    {
        parent::__construct($method, $parameters);

        $this->instance = $instance;
    }

    /**
     * Get the instance.
     *
     * @return  mixed
     */
    public function getInstance()
    {
        return $this->instance;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'instance' => $this->getInstance()
        ]);
    }

}
