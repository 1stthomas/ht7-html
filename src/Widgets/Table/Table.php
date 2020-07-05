<?php

namespace Ht7\Html\Widgets\Table;

use \Ht7\Html\Widgets\Viewable;
use \Ht7\Html\Widgets\Table\Modelable;
use \Ht7\Html\Widgets\Table\Models\Simple as ModelSimple;

/**
 * Description of Table
 *
 * @author Thomas Plüss
 */
class Table implements \JsonSerializable
{

    protected $model;
    protected $view;

    public function __construct(Modelable $model = null)
    {
        if (empty($model)) {
            $model = new ModelSimple();
        }
        $this->setModel($model);

        $this->view = new View($this->getModel());
    }

    /**
     * Get the model of the present widget.
     *
     * @return  Modelable           The model implementation.
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the view element of the present widget.
     *
     * @return type
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * {inheritdoc}
     */
    public function jsonSerialize()
    {
        $view = $this->getView();
        $view->render();

        return $view->getItemList()->jsonSerialize();
    }

    public function render()
    {
        return $this->getView()->render();
    }

    /**
     * Set the model instance of the current table widget.
     *
     * @param   Modelable   $model      The data model.
     */
    public function setModel(Modelable $model)
    {
        $this->model = $model;
    }

    /**
     * Set the view instance of the current table widget.
     *
     * @param   Viewable    $view       The view instance.
     */
    public function setView(Viewable $view)
    {
        $this->view = $view;
    }

}
