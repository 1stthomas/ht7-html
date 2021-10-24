<?php

namespace Ht7\Html\Widgets;

use \Ht7\Html\Renderable;
use \Ht7\Html\Lists\NodeList;

/**
 * Basic view component of a ht7 widget.
 *
 * @author Thomas Pluess
 */
abstract class AbstractView implements Renderable, Viewable
{

    /**
     * True if the NodeList has already been generated.
     *
     * @var     boolean
     */
    protected $isTransformed;

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
     * Create an instance of the table view class.
     *
     * @param   Modelable   $model      The data model.
     * @param   NodeList    $iL         The tag element list.
     */
    public function __construct(Modelable $model = null, NodeList $iL = null)
    {
        $this->isTransformed = false;

        if ($model !== null) {
            $this->setModel($model);
        }
        if (empty($iL)) {
            $iL = new NodeList();
        }

        $this->setItemList($iL);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return '' . $this->render();
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
     * Reset the tag element list to respect newly added model definitions.
     *
     * This method does only its job if <code>$this->isTransformed == true</code>.
     */
    public function reset()
    {
        if ($this->isTransformed) {
            $iL = new NodeList();
            $this->setItemList($iL);
            $this->isTransformed = false;
        }
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

    /**
     * Transform the model data into Ht7 Tag readable and create the tags
     * accordingly.
     */
    abstract protected function transform();
}
