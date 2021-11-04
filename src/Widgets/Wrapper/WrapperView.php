<?php

namespace Ht7\Html\Widgets\Wrapper;

use \Ht7\Html\Utilities\ImporterArray;
use \Ht7\Html\Widgets\AbstractWidgetView;
use \Ht7\Html\Widgets\Modelable;
use \Ht7\Html\Widgets\Wrapper\WrapperModel;

/**
 *
 */
class WrapperView extends AbstractWidgetView
{

    public function __construct(Modelable $model = null)
    {
        if (empty($model)) {
            $model = new WrapperModel();
        }

        parent::__construct($model);
    }

    protected function transform()
    {
        /* @var $model WrapperModel */
        $model = $this->getModel();
        /* @var $importer ImporterArray */
        $importer = ImporterArray::getInstance();
        $importer->getDefaults()->setCallback($model->getCallback());

        $tag = $importer->import($model->getMarkup());

        $this->getItemList()->add($tag);
        $this->setIsTransformed(true);
    }

}
