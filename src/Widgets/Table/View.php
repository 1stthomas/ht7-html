<?php

namespace Ht7\Html\Widgets\Table;

use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Translators\ImporterHt7;
use \Ht7\Html\Widgets\View as ViewBase;
use \Ht7\Html\Widgets\Table\Modelable;
use \Ht7\Html\Widgets\Table\Utility\RowTypes;

/**
 * Description of View
 *
 * @author Thomas Plüss
 */
class View extends ViewBase
{

    /**
     * True if the NodeList has already been generated.
     *
     * @var     boolean
     */
    protected $isTransformed;

    /**
     * Create an instance of the table view class.
     *
     * @param   Modelable   $model      The data model.
     * @param   NodeList    $iL         The tag element list.
     */
    public function __construct(Modelable $model = null, NodeList $iL = null)
    {
        $this->isTransformed = false;

        if ($model !== null) {
            $this->setModel($model);
        }
        if (empty($iL)) {
            $iL = new NodeList();
        }

        $this->setItemList($iL);
    }

    /**
     * Reset the tag element list to respect newly added model definitions.
     *
     * This method does only its job if <code>$this->isTransformed == true</code>.
     */
    public function reset()
    {
        if ($this->isTransformed) {
            $iL = new NodeList();
            $this->setItemList($iL);
            $this->isTransformed = false;
        }
    }

    /**
     * Create an array of tag definitions of a table row.
     *
     * @param   mixed   $rowType    The row type of the current row.
     * @param   array   $cells      The cells of the current row.
     * @return  array               The definitions of the current table row.
     */
    protected function createRow($rowType, array $cells = [])
    {
        $row = [];

        if (!empty($cells)) {
            $row['content'] = [];
            $row['tag'] = 'tr';

            foreach ($cells as $cell) {
                $tag = RowTypes::getRowType($rowType) === RowTypes::HEADER ? 'th' : 'td';

                $row['content'][] = [
                    'content' => $cell,
                    'tag' => $tag
                ];
            }
        }

        return $row;
    }

    /**
     * Create the rows of a specific row type.
     *
     * @param   mixed   $rowType        The row type of the current rows.
     * @param   array   $rows           The rows with its cells.
     * @return  array                   The tag element definitions of the rows.
     */
    protected function createRows($rowType, array $rows = [])
    {
        $structure = [];

        if (!empty($rows)) {
            if (is_array($rows[0])) {
                foreach ($rows as $row) {
                    $structure[] = $this->createRow($rowType, $row);
                }
            } else {
                $structure[] = $this->createRow($rowType, $rows);
            }
        }

        return $structure;
    }

    /**
     * Transform the model data into Ht7\Html\Tag elements.
     *
     * This method will fill the NodeList and set <code>$this->isTransformed = true;</code>
     */
    protected function transform()
    {
        $data = $this->model->getData();

        $definitions = [
            'attributes' => [
                'class' => 'table table-responsive'
            ],
            'content' => [],
            'tag' => 'table'
        ];

        $i = 0;

        foreach ($data as $rowType => $rows) {
            $els = $this->createRows($rowType, $rows);

            if (!empty($els)) {
                $definitions['content'][$i] = [
                    'content' => $els,
                    'tag' => RowTypes::getRowTypeTagName($rowType)
                ];

                $i++;
            }
        }

        $this->itemList->add(ImporterHt7::readFromArray($definitions));

        $this->isTransformed = true;
    }

}
