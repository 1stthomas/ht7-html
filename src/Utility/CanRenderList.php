<?php

namespace Ht7\Html\Utility;

use \InvalidArgumentException;
use \Ht7\Base\Validation\ExceptionTrigger;
use \Ht7\Base\Validation\ExceptionMessagePointer;

/**
 *
 * @author Thomas Plüss
 */
trait CanRenderList
{

    protected $divider;

    public function getDivider()
    {
        return $this->divider;
    }

    public function setDivider($divider)
    {
        if (is_string($divider)) {
            $this->divider = $divider;
        } else {
            ExceptionTrigger::trigger(
                    ExceptionMessagePointer::WRONG_DATATYPE,
                    ['The test', 'string', 'object']
            );

//            $msg = 'The divider needs to be a string, found %s.';
//            $e = sprintf($msg, gettype($divider));
//
//            throw new InvalidArgumentException($e);
        }
    }

    public function __toString()
    {
        return implode($this->getDivider(), $this->items);
    }

}
