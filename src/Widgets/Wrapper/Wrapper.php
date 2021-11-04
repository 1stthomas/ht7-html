<?php

namespace Ht7\Html\Widgets\Wrapper;

use \Ht7\Html\Widgets\AbstractWidgetController;
use \Ht7\Html\Widgets\Modelable;
use \Ht7\Html\Widgets\Viewable;
use \Ht7\Html\Widgets\Wrapper\WrapperModel;
use \Ht7\Html\Widgets\Wrapper\WrapperView;

/**
 *
 */
class Wrapper extends AbstractWidgetController
{

    public function __construct(Modelable $model = null, Viewable $view = null)
    {
        if (empty($model)) {
            $model = new WrapperModel();
        }
        if (empty($view)) {
            $view = new WrapperView($model);
        }

        parent::__construct($model, $view);

        $this->setupModel($model);
    }

    public function replace($data = null)
    {
        return (string) $this->getModel()->getItem($data['id']);
    }

    protected function setupModel(Modelable $model)
    {
        if (empty($model->getCallback())) {
            $callback = [
                'instance' => $this,
                'method' => 'replace',
            ];

            $model->setCallback($callback);
        }
    }

}
