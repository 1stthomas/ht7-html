<?php

namespace Ht7\Html\Widgets;

use \Ht7\Html\Lists\NodeList;

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
     * Transform the model data into the Tag instances.
     *
     * @return  NodeList            The filled list of tag elements.
     */
    public function render();

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
