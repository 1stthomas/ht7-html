<?php

namespace Ht7\Html\Utilities;

use \Ht7\Base\Exceptions\InvalidDatatypeException;

/**
 *
 * @author Thomas Plüss
 */
trait CanRenderList
{
    protected $divider = '';

    public function getDivider()
    {
        return $this->divider;
    }
    public function setDivider($divider)
    {
        if (is_string($divider)) {
            $this->divider = $divider;
        } else {
            throw new InvalidDatatypeException('divider', $divider, ['string']);
        }
    }
    public function __toString()
    {
        return implode($this->getDivider(), $this->getAll());
    }
}
