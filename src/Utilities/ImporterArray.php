<?php

namespace Ht7\Html\Utilities;

use \Ht7\Html\Callback;
use \Ht7\Html\Node;
use \Ht7\Html\Replacer;
use \Ht7\Html\Tag;
use \Ht7\Html\Text;
use \Ht7\Html\Lists\NodeList;
use \Ht7\Html\Models\ImporterArrayDefaultsModel;
use \Ht7\Html\Utilities\AbstractImporter;

/**
 * This class can transform several kinds of tag definitions into a ht7 tag tree.
 *
 * @author Thomas Plüss
 */
class ImporterArray extends AbstractImporter
{

    /**
     * Importer defaults.
     *
     * @var ImporterArrayDefaultsModel
     */
    protected $defaults;

    /**
     * Create an instance of the <code>ImporterArray</code> class.
     *
     * @param array|ImporterArrayDefaultsModel $defaults
     */
    public function __construct($defaults = [])
    {
        if (!is_object($defaults) || !($defaults instanceof ImporterArrayDefaultsModel)) {
            $defaults = new ImporterArrayDefaultsModel($defaults);
        }

        $this->defaults = $defaults;
    }

    /**
     * Create a <code>\Ht7\Html\Tag</code> instance with the present data.
     *
     * An exception will be thrown in case the "tag" key is missing.
     *
     * @param   array   $data           Assoc array with in minimum a "tag" key.
     *                                  "attributes" and "content" keys are optional.
     * @return  Tag
     * @throws  \InvalidArgumentException
     */
    public function createTag(array $data)
    {
        if (empty($data['tag'])) {
            $e = 'Missing "tag" key.';

            throw new \InvalidArgumentException($e);
        }

        $content = isset($data['content']) ? $data['content'] : [];
        $attributes = isset($data['attributes']) ? $data['attributes'] : [];

        return new Tag($data['tag'], $content, $attributes);
    }

    /**
     * Create a <code>\Ht7\Html\Text</code> instance with the present data.
     *
     * An exception will be thrown in case the parameter is not a scalar type.
     *
     * @param   string|int|float    $text   The content.
     * @return  Text                        The node with the present content.
     * @throws \InvalidArgumentException
     */
    public function createText($text)
    {
        if (is_scalar($text)) {
            return new Text($text);
        }

        $e = 'Only scalar types are supported. Found: ' . gettype($text);

        throw new \InvalidArgumentException($e);
    }

    /**
     * Create a typed node.
     *
     * Supported types:
     * - 'callback'
     *
     * @param   array       $arr            Assoc array with in minimum the key
     *                                      "type". For other keys see the related
     *                                      documentation.
     * @return  Callback                    The new instance.
     * @throws  \InvalidArgumentException
     */
    public function createTypedElement(array $arr)
    {
        if ($arr['type'] === 'callback') {
            return new Callback($arr);
        } elseif ($arr['type'] === 'replacer') {
            $arr['callback'] = empty($arr['callback']) ?
                    $this->getDefaults()->getCallback() :
                    $arr['callback'];

            return new Replacer($arr);
        } else {
            $e = 'Unsupported node type "' . $arr['type'] . '".';

            throw new \InvalidArgumentException($e);
        }
    }

    /**
     * Get the defaults of the present importer.
     *
     * @return ImporterArrayDefaultsModel   A model object with the importer defaults.
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * Build a tag tree from an array.
     *
     * Use this method if the root contains tag informations, e.g.:
     * <pre><code>
     * $arr = [
     *     'tag' => 'div',
     *     'content' => ['simple text'],
     *     'attributes' => ['class' => 'btn', 'id' => 'btn-123']
     * ];
     * </code></pre>
     * will transform into<br />
     * <code>&lt;div class="btn" id="btn-123"&gt;simple text&lt;&#47;div&gt;</code>.
     *
     * @param   string|array|Node   $input
     * @return  Node|Text|Tag
     * @throws  \InvalidArgumentException
     */
    public function import($input)
    {
        if (empty($input)) {
            return null;
        } elseif (is_scalar($input)) {
            return $this->createText($input);
        } elseif (is_array($input)) {
            if ($this->isIndexed($input)) {
                return $this->importNodeList($input);
            } else {
                if (isset($input['tag'])) {
                    return $this->createTag($input);
                } elseif (isset($input['type'])) {
                    echo "typed el...\n";
                    return $this->createTypedElement($input);
                } else {
                    $e = 'Missing "tag" or "type" key. Found only: ["'
                            . implode('", "', array_keys($input)) . '"]';

                    throw new \InvalidArgumentException($e);
                }
            }
        } elseif (is_object($input) && $input instanceof Node) {
            return $input;
        } else {
            $e = 'Unsupported ' . (is_object($input) ?
                    'class: ' . get_class($input) : 'type: ' . gettype($input));

            throw new \InvalidArgumentException($e);
        }
    }

    /**
     * Import an indexed array of element informations.
     *
     * @param   array   $arr
     * @param   bool    $createNodeList         True if a <code>\Ht7\Html\Lists\NodeList</code>
     *                                          should be created.
     * @return  array|NodeList                  An indexed array if the boolean
     *                                          parameter is false, otherwise
     *                                          a new created <code>\Ht7\Html\Lists\NodeList</code>.
     */
    public function importNodeList(array $arr = [], bool $createNodeList = false)
    {
        $items = [];

        foreach ($arr as $item) {
            $items[] = $this->import($item);
        }

        return $createNodeList ? new NodeList($items) : $items;
    }

    /**
     * Check wheter the present array is indexed or assoc.
     *
     * @param   array       $arr        The array to check.
     * @return  bool                    True if the present array is indexed.
     */
    protected function isIndexed(array $arr)
    {
        return array_keys($arr) === array_keys(array_keys($arr));
    }

}
