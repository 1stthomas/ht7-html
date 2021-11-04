<?php

namespace Ht7\Html\Models;

use \Ht7\Html\Models\AbstractCallbackModel;

/**
 *
 */
class CallbackWithMethodModel extends AbstractCallbackModel
{

    protected $method;

    /**
     * {@inheritdoc}
     *
     * @param   mixed   $method         The method name as string.
     */
    public function __construct($method, array $parameters = [])
    {
        parent::__construct($parameters);

        $this->method = $method;
    }

    /**
     * Get the callback method.
     *
     * @return  mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'method' => $this->getMethod()
        ]);
    }

}
