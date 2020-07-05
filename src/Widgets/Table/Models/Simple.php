<?php

namespace Ht7\Html\Widgets\Table\Models;

use \Ht7\Html\Widgets\Table\Model as ModelTableBase;
use \Ht7\Html\Widgets\Table\Utility\RowTypes;

/**
 * A model for a table without special settings (no col-/rowspans, no attributes,
 * no additional cell elements). Header and footer cells can be added but must
 * have the same column count as the body.
 * No validation of the input is processed while adding the data.
 *
 * @author Thomas Pluess
 */
class Simple extends ModelTableBase
{

    /**
     * {@inheritdoc}
     */
    public function addRow(array $row, $type = RowTypes::BODY)
    {
        $typeTransformed = RowTypes::getRowType($type);

        switch ($typeTransformed) {
            case RowTypes::FOOTER:
                if (!isset($this->data[RowTypes::FOOTER])) {
                    $this->data[RowTypes::FOOTER] = [];
                }

                $this->data[RowTypes::FOOTER][] = $row;
                break;
            case RowTypes::HEADER:
                if (!isset($this->data[RowTypes::HEADER])) {
                    $this->data[RowTypes::HEADER] = [];
                }

                $this->data[RowTypes::HEADER][] = $row;
                break;
            case RowTypes::BODY:
                if (!isset($this->data[RowTypes::BODY])) {
                    $this->data[RowTypes::BODY] = [];
                }

                $this->data[RowTypes::BODY][] = $row;
                break;
        }
    }

}
