<?php

namespace Ht7\Html\Widgets\Wrapper;

use \Ht7\Html\Widgets\AbstractWidgetModel;

/**
 * Simple model for the wrapper widget.
 *
 * It provides following properties:
 * - callback
 * - items
 * - markup
 */
class WrapperModel extends AbstractWidgetModel
{

    /**
     *
     * @var array               Assoc array with callback definitions.
     */
    protected $callback;

    /**
     *
     * @var array               Assoc array with the items which will replace
     *                          the callback nodes.
     */
    protected $items;

    /**
     *
     * @var     array           The markup to use by the present wrapper.
     */
    protected $markup;

    /**
     * Create an instance of the <code>WrapperModel</code> class.
     *
     * @param   array   $markup     Assoc array with callback definitions.
     * @param   array   $items      Assoc array with the items which will replace
     *                              the callback nodes.
     * @param   array   $callback   Assoc array with callback definitions.
     */
    public function __construct(array $markup = [], array $items = [], array $callback = [])
    {
        $this->setMarkup($markup);
        $this->setItems($items);
        $this->setCallback($callback);

        parent::__construct([]);
    }

    /**
     * Add an item which will replace the callback node.
     *
     * @param   string  $key
     * @param   mixed   $value
     * @return  $this
     */
    public function addItem(string $key, $value)
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Get the callback of the present wrapper.
     *
     * @return  array               Assoc array with the <code>\Ht7\Html\Callback</code>
     *                              node definitions.
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Get an item by the present key (id).
     *
     * @param string $key
     * @return type
     * @throws \InvalidArgumentException
     */
    public function getItem(string $key)
    {
        if (!isset($this->items[$key])) {
            $e = 'Missing item with key ' . $key . '.';

            throw new \InvalidArgumentException($e);
        }

        return $this->items[$key];
    }

    /**
     * Get the items of the present wrapper.
     *
     * @return  array                   Assoc array.
     */
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

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->setItems([]);
    }

    /**
     * Set the definitions of the callback node.
     *
     * @param   array   $callback           Assoc array with callback definitions.
     */
    public function setCallback(array $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Set the items of the present wrapper.
     *
     * @param   array   $items              Assoc array of ids as keys and the elements
     *                                      wich will be used by the callback.
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }

    /**
     * Set the markup of the present wrapper.
     *
     * @param   array   $markup             Assoc array of <code>\Ht7\Html\Node</code> definitions.
     */
    public function setMarkup(array $markup)
    {
        $this->markup = $markup;
    }

}
