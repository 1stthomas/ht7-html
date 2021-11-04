<?php

namespace Ht7\Html\Widgets\Wrapper;

use \Ht7\Html\Widgets\Wrapper\Wrapper;
use \Ht7\Html\Widgets\Wrapper\Markups\Bootstrap;

/**
 *
 */
class WrapperFactory
{

    /**
     * The used classes for the markup.
     *
     * @var array               Assoc array with the framework as key and the
     *                          the absolute class name to use.
     */
    protected $mappings;

    public function __construct(array $mappings = [])
    {
        if (empty($mappings)) {
            $mappings = $this->getDefaultMappings();
        }

        $this->setMappings($mappings);
    }

    public function create(array $markup, array $items = [])
    {
        $wrapper = $this->createWrapperPlain();

        $wrapper->getModel()->setMarkup($markup);

        if (!empty($items)) {
            $wrapper->getModel()->setItems($items);
        }

        return $wrapper;
    }

    public function createBootstrapRow(array $items = [], string $rowType = 'ROW_SIMPLE_V3_12_6', bool $getWidget = true)
    {
        $widget = $this->create(
                constant($this->getMapping('bootstrap') . '::' . $rowType),
                $items
        );

        return $getWidget ? $widget : $widget->getView()->render()->get(0);
    }

    public function createWrapperPlain()
    {
        return new Wrapper();
    }

    /**
     * Get the default framework to class mappings.
     *
     * @return  array                   Assoc array with the framework as key and the
     *                                  the absolute class name to use.
     */
    public function getDefaultMappings()
    {
        return [
            'bootstrap' => Bootstrap::class,
        ];
    }

    public function getMapping(string $key)
    {
        return $this->mappings[$key];
    }

    public function reset()
    {
        $this->setMappings($this->getDefaultMappings());
    }

    /**
     * Set the mappings of framework to classes to be used.
     *
     * @param   array   $mappings       Assoc array with the framework as key and the
     *                                  the absolute class name to use.
     */
    public function setMappings(array $mappings)
    {
        $this->mappings = array_merge($this->mappings, $mappings);
    }

}
