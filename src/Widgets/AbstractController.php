<?php

namespace Ht7\Html\Widgets;

use \Ht7\Html\Widgets\Modelable;
use \Ht7\Html\Widgets\Viewable;

/**
 * Basic widget controller.
 *
 * @author Thomas Pluess
 */
abstract class AbstractController implements \JsonSerializable
{

    /**
     * The data container.
     *
     * @var Modelable
     */
    protected $model;

    /**
     * The component with the compose instructions.
     *
     * @var Viewable
     */
    protected $view;

    /**
     * Create a widget instance.
     *
     * @param Modelable $model
     * @param Viewable $view
     */
    public function __construct(Modelable $model = null, Viewable $view = null)
    {
        $this->setModel($model);

        $this->setView($view);
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

    /**
     *
     * @return \Ht7\Html\Lists\NodeList;
     */
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
