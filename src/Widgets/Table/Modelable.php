<?php

namespace Ht7\Html\Widgets\Table;

use \Ht7\Html\Widgets\Modelable as ModelableBase;
use \Ht7\Html\Widgets\Table\Utility\RowTypes;

/**
 * This interface defines all needed methods for the table widget model to work.
 *
 * @author Thomas Pluess
 */
interface Modelable extends ModelableBase
{

    /**
     * Add a row to the table-body, -footer or -header.
     *
     * @param   array   $row        Indexed array of table cells. The array item
     *                              content can be either a string or a tag
     *                              instance of this package.
     * @param   mixed   $type       The row type. Use one of the types defined
     *                              by the following enum:
     *                              <code>\Ht7\Html\Widgets\Table\Utility\RowTypes</code>.
     *                              Default value: <code>RowTypes::BODY</code>.
     */
    public function addRow(array $row, $type = RowTypes::BODY);
}
