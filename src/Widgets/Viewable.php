<?php

namespace Ht7\Html\Widgets;

use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Widgets\Modelable;

/**
 *
 * @author Thomas Pluess
 */
interface Viewable
{

    /**
     * Get the tag elements of the present widget.
     *
     * @return  NodeList            The list of tag elements.
     */
    public function getItemList();

    /**
     * Get the model.
     *
     * @return  Modelable           The model of the present view.
     */
    public function getModel();

    /**
     * Transform the model data into the Tag instances.
     *
     * @return  NodeList            The filled list of tag elements.
     */
    public function render();

    /**
     * Reset the present view.
     */
    public function reset();

    /**
     * Set the element list of the present widget.
     *
     * @param NodeList $iL
     */
    public function setItemList(NodeList $iL);

    /**
     * Set the model of the view of the present widget.
     *
     * @param \Ht7\Html\Widgets\Modelable $model
     */
    public function setModel(Modelable $model);
}
