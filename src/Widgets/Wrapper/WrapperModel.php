<?php

namespace Ht7\Html\Widgets\Wrapper;

use \Ht7\Html\Widgets\AbstractWidgetModel;
use \Ht7\Html\Widgets\Viewable;
use \Ht7\Html\Widgets\Modelable;

/**
 *
 */
class WrapperModel extends AbstractWidgetModel
{

    protected $callback;
    protected $items;
    protected $markup;

    public function __construct(array $markup = [], array $items = [], array $callback = [])
    {
        $this->setMarkup($markup);
        $this->setItems($items);
        $this->setCallback($callback);

        parent::__construct([]);
    }

    public function addItem(string $key, $value)
    {
        $this->items[$key] = $value;

        return $this;
    }

    public function getCallback()
    {
        return $this->callback;
    }

    public function getItem(string $key)
    {
        if (!isset($this->items[$key])) {
            $e = 'Missing item with key ' . $key . '.';

            throw new \InvalidArgumentException($e);
        }

        return $this->items[$key];
    }

    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get the markup of the wrapper.
     *
     * @return  array               Assoc array of html markup definitions
     */
    public function getMarkup()
    {
        return $this->markup;
    }

    public function setCallback(array $callback)
    {
        $this->callback = $callback;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
    }

    public function setMarkup(array $markup)
    {
        $this->markup = $markup;
    }

}
