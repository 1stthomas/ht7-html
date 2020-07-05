<?php

namespace Ht7\Html\Widgets;

use \Ht7\Html\Renderable;
use \Ht7\Html\Lists\NodeList;

/**
 *
 *
 * @author Thomas Pluess
 */
abstract class View implements Renderable, Viewable
{

    /**
     * The list of the tag elements.
     *
     * @var     NodeList            The list of tags according to the model definitions.
     */
    protected $itemList;

    /**
     *
     * @var     Modelable           The model of the present widget.
     */
    protected $model;

    /**
     * {inheritdoc}
     */
    public function __toString()
    {
        '' . $this->render();
    }

    /**
     * Get the tag elements of the present widget.
     *
     * @return  NodeList            The list of tag elements.
     */
    public function getItemList()
    {
        return $this->itemList;
    }

    /**
     * Transform the model data into the Tag instances.
     *
     * @return  NodeList            The filled list of tag elements.
     */
    public function render()
    {
        if (!$this->isTransformed) {
            $this->transform();
        }

        return $this->getItemList();
    }

    /**
     * Set the element list of the present widget.
     *
     * @param NodeList $iL
     */
    public function setItemList(NodeList $iL)
    {
        $this->itemList = $iL;
    }

    /**
     * Set the model of the view of the present widget.
     *
     * @param \Ht7\Html\Widgets\Modelable $model
     */
    public function setModel(Modelable $model)
    {
        $this->model = $model;
    }

}
