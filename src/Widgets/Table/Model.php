<?php

namespace Ht7\Html\Widgets\Table;

use \Ht7\Html\Widgets\Model as ModelBase;
use \Ht7\Html\Widgets\Table\Utility\RowTypes;

/**
 * Das duerfte ueberfluessig sein..
 *
 * @author Thomas Pluess
 */
abstract class Model extends ModelBase implements Modelable
{

    /**
     * {@inheritdoc}
     */
    abstract public function addRow(array $row, $type = RowTypes::BODY);
}
