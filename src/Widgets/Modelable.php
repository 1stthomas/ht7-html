<?php

namespace Ht7\Html\Widgets;

/**
 * Method definitions of a basic widget model.
 *
 * @author Thomas Pluess
 */
interface Modelable
{

    /**
     * Get the model data.
     * @return  array           The model data.
     */
    public function getData();

    /**
     * Set the model data.
     * @param   array   $data   The model data.
     */
    public function setData(array $data);
}
