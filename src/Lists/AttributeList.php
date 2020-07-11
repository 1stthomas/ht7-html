<?php

namespace Ht7\Html\Lists;

use \InvalidArgumentException;
use \Ht7\Base\Lists\HashList;
use \Ht7\Html\Attribute;
use \Ht7\Html\Utilities\CanRenderList;
use \Ht7\Html\Renderable;

/**
 * Description of ItemList
 *
 * @author Thomas Plüss
 */
class AttributeList extends HashList implements \JsonSerializable, Renderable
{

    use CanRenderList;

    public function __construct(array $data = [])
    {
        $this->divider = ' ';

        parent::__construct($data);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $all = $this->getAll();
        ksort($all);

        return implode($this->getDivider(), $all);
    }

    /**
     * Add an attribute to the present AttributeList.
     *
     * Only instances of the Attribute class will be accepted.
     *
     * @Overridden
     */
    public function add($item)
    {
        if ($item instanceof Attribute) {
            parent::add($item);

            return $this;
        } else {
            $msg = 'The item must be an instance of the Attribute class, found %s.';
            $e = sprintf($msg, gettype($item));

            throw new InvalidArgumentException($e);
        }
    }

    /**
     * Add an attribute name and its value to the present AttributeList.
     *
     * @param   string  $name           The attribute name.
     * @param   string  $value          The attribute value.
     * @return  AttributeList           The present AttributeList instance.
     */
    public function addPlain($name, $value)
    {
        return $this->add((new Attribute($name, $value)));
    }

    /**
     * Check wheter a value can be found or not in the present AttributeList instance.
     *
     * @param   string  $compare        The search value.
     * @return  boolean                 True if an attribute with the present
     *                                  search value could be found.
     */
    public function hasByValue($compare)
    {
        /* @var $attr Attribute */
        foreach ($this->items as $attr) {
            $value = $attr->getValue();

            if ($value === $compare) {
                return true;
            } elseif (empty($value) && $attr->getName() === $compare) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->getAll();
    }

    /**
     * Load the present AttributeList instance with the submitted data.
     *
     * @Overridden
     */
    public function load(array $data)
    {
        foreach ($data as $key => $value) {
            if ($value instanceof Attribute) {
                $this->add($value);
            } else {
                if (is_integer($key) || empty($key)) {
                    $key = $value;
                    $value = '';
                }

                $this->addPlain($key, $value);
            }
        }
    }

}
