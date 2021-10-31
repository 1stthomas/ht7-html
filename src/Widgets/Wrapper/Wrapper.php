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

        $markup = [
            'tag' => 'div',
            'attributes' => ['class' => 'container'],
            'content' => [
                [
                    'tag' => 'div',
                    'attributes' => ['class' => 'col-xs-12 col-sm-6'],
                    'content' => [
                        [
                            'type' => 'replacer',
                            'id' => 'test-12345'
                        ],
                    ],
                ],
                [
                    'tag' => 'div',
                    'attributes' => ['class' => 'col-xs-12 col-sm-6'],
                    'content' => [
                        [
                            'type' => 'replacer',
                            'id' => 'test-67890'
                        ],
                    ],
                ],
            ],
        ];

        $model->setMarkup($markup);
    }

}
