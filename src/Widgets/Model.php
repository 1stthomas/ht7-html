<?php

namespace Ht7\Html\Widgets;

/**
 *
 * @author Thomas Pluess
 */
abstract class Model implements Modelable
{

    /**
     * The model data.
     *
     * @var array           Structured content.
     */
    protected $data;

    /**
     * Create a model instance.
     *
     * @param   array   $data   The model data.
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    /**
     * Get the model data.
     *
     * @return  array           The model definitions.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the model data.
     * 
     * @param   array   $data   The model data.
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

}
