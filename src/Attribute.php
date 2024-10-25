<?php

namespace Ht7\Html;

use Ht7\Base\Lists\Hashable;
use Ht7\Html\Renderable;

class Attribute implements Hashable, \JsonSerializable, Renderable
{
    /**
     * Create an instance of the attribute class.
     */
    public function __construct(protected string $name, protected string|float|int|bool $value)
    {
    }
    /**
     * Get a string representation of the current class.
     *
     * @return  string                  The output will be as following:
     *                                  <code>attributeName="AttributeValue"</code>.
     */
    public function __toString()
    {
        $value = $this->getValue();

        return $this->getName() . ($value === '' ? '' : '="' . $value . '"');
    }
    /**
     * @Overridden
     */
    public function getHash()
    {
        return $this->getName();
    }
    /**
     * Get the name of the present attribute.
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * Get the value of the present attribute.
     */
    public function getValue(): string|float|int|bool
    {
        return $this->value;
    }
    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): mixed
    {
        return $this->getValue();
    }
    /**
     * Set the name of the current attribute instance.<br />
     * The name must not be empty.
     */
    public function setName(string $name): static
    {
        if (empty($name)) {
            $e = 'The attribute name must not be empty.';

            throw new \InvalidArgumentException($e);
        }
        
        $this->name = $name;

        return $this;
    }
    /**
     * Set the value of the current attribute instance.<br />
     * The value must be either string, float, int or bool.
     */
    public function setValue(string|float|int|bool $value): static
    {
        $this->value = $value;

        return $this;
    }
}
