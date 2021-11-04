<?php

namespace Ht7\Html;

use \InvalidArgumentException;
use \Ht7\Html\Node;
use \Ht7\Html\Models\CallbackWithCallableModel;
use \Ht7\Html\Models\CallbackWithInstanceModel;
use \Ht7\Html\Models\CallbackWithMethodModel;

/**
 * Description of Attribute
 *
 * @author 1stthomas
 */
class Callback extends Node
{

    protected $model;

    /**
     * Create an instance of the callback element.
     *
     * @param   string|array    $content
     */
    public function __construct($content)
    {
        $this->setContent($content);
    }

    /**
     * Get a string representation of the current class.
     *
     * @return  string                  The content.
     */
    public function __toString()
    {
        return $this->process();
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->process();
//        return $this->getModel();
    }

    /**
     * Set the content.
     *
     * Allowed datatypes:
     * - string
     * - integer
     * - float
     *
     * @param   string  $content            The content.
     * @throws  InvalidArgumentException
     */
    public function setContent($content)
    {
        if (is_string($content)) {
            $content = ['method' => $content];
        } elseif (is_callable($content)) {
            $content = ['callable' => $content];
        }

        $this->createModel($content);
    }

    protected function createModel(array $content)
    {
        $parameters = isset($content['parameters']) ?
                $content['parameters'] : [];

        if (!empty($content['callable'])) {
            $this->model = new CallbackWithCallableModel($content['callable'], $parameters);
        } elseif (empty($content['instance'])) {
            if (empty($content['method'])) {
                $e = 'Missing method.';

                throw new \InvalidArgumentException($e);
            }

            $this->model = new CallbackWithMethodModel($content['method'], $parameters);
        } else {
            if (empty($content['method'])) {
                $e = 'Missing method.';

                throw new \InvalidArgumentException($e);
            }

            $this->model = new CallbackWithInstanceModel(
                    $content['instance'],
                    $content['method'],
                    $parameters
            );
        }
    }

    protected function process()
    {
        $model = $this->getModel();

        if ($model instanceof CallbackWithInstanceModel) {
            return call_user_func_array(
                    [$model->getInstance(), $model->getMethod()],
                    [$model->getParameters()]
            );
        } elseif ($model instanceof CallbackWithMethodModel) {
            return $model->getMethod()($model->getParameters());
        } elseif ($model instanceof CallbackWithCallableModel) {
            return call_user_func(
                    $model->getCallable(),
                    [$model->getParameters()]
            );
        }

        if (empty($model)) {
            $e = 'Missing model.';

            throw new \BadMethodCallException($e);
        }

        $e = 'Unsupported model '
                . is_object($model) ? get_class($model) : gettype($model);

        throw new \BadMethodCallException($e);
    }

}
