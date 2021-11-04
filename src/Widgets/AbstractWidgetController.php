<?php

namespace Ht7\Html\Widgets;

use \Ht7\Html\Renderable;
use \Ht7\Html\Widgets\Modelable;
use \Ht7\Html\Widgets\Viewable;

/**
 * Basic widget controller.
 *
 * @author Thomas Pluess
 */
abstract class AbstractWidgetController implements \JsonSerializable, Renderable
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
     * In case both parameters are objects, the model will be set to the view.
     *
     * @param   Modelable   $model
     * @param   Viewable    $view
     */
    public function __construct(Modelable $model = null, Viewable $view = null)
    {
        $this->setModel($model);
        $this->setView($view);

        if (is_object($model) && is_object($view)) {
            $view->setModel($model);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->getView();
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
        return $this->getView()
                        ->render()
                        ->jsonSerialize();
    }

    public function reset()
    {
        $this->getModel()->reset();
        $this->getView()->reset();
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
