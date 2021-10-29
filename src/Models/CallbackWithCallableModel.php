<?php

namespace Ht7\Html\Models;

use \Ht7\Html\Models\AbstractCallbackModel;

/**
 *
 */
class CallbackWithCallableModel extends AbstractCallbackModel
{

    protected $callable;

    /**
     * {@inheritdoc}
     *
     * @param   callable    $callable       The callable.
     */
    public function __construct($callable, array $parameters = [])
    {
        parent::__construct($parameters);

        $this->callable = $callable;
    }

    /**
     * Get the callable.
     *
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'callable' => $this->getCallable()
        ]);
    }

}
