<?php

namespace Ht7\Html\Tags;

use \Ht7\Base\Exceptions\InvalidDatatypeException;
use \Ht7\Html\Tag;
use \Ht7\Html\Lists\NodeList;

/**
 * This class generates the HTML markup of a select tag with few input.
 *
 * To use it, just instanciate this class with the desired select and option
 * informations:
 *
 * <code>
 * $options = [['test 1', '1'], ['test 2', '2', 1], ['test 3', '3', 0, ['data-url' => 'test/file.png']]];<br />
 * $select = new Select($options, ['class' => 'form-control']);<br />
 * echo $select;
 * </code>
 *
 * Instead of an indexed array of options, this can also be done as following:
 *
 * <code>
 * $options = [<br />
 * &nbsp;&nbsp;&nbsp;&nbsp; ['content' => 'test 1', 'attributes' => ['value' => '1']],<br />
 * &nbsp;&nbsp;&nbsp;&nbsp; ['content' => 'test 2', 'attributes' => ['selected' => '', 'value' => '2']],<br />
 * &nbsp;&nbsp;&nbsp;&nbsp; ['content' => 'test 3', 'attributes' => ['data-url' => 'test/file.png', 'value' => '3']]<br />
 * ];
 * </code>
 *
 * Besides the already shown, this class supports also the optgroup elements. To
 * use them, just group the desired options into additional arrays, while the
 * related key will be their label.
 *
 * 
 * @author 1stthomas
 */
class Select extends Tag
{

    /**
     * Create an instance of the Select class.
     *
     * @param   mixed   $content        The content of the current select instance
     *                                  as string, int, float or indexed array of
     *                                  further Tag instances.
     * @param   array   $attributes     Indexed array of Attribute instances, or
     *                                  assoc array with the attribute names as
     *                                  keys and their values as values.
     */
    public function __construct($content = [], $attributes = [])
    {
        parent::__construct('select', $content, $attributes);
    }

    /**
     * Add an option to the current select element.
     *
     * The array can be associated or indexed. The information is taken from
     * following array keys:
     * - <b>indexed:</b> [0] => display name; [1] => value attribute; [2] => if
     * <code>!empty($option[2])</code> then selected; [3] => further option attributes.
     * - <b>associated:</b> 'content' => display name; 'attributes' => the
     * attribute definitions according to <code>\Ht7\Html\Attribute</code>.
     *
     * @param array $option
     * @throws  InvalidDatatypeException
     */
    public function add($option, $container = null)
    {
        if (is_array($option) && !empty($option)) {
            if (isset($option[0])) {
                $content = is_array($option[0]) ? $option[0] : [$option[0]];
                $attributes = ['value' => $option[1]];

                if (!empty($option[2])) {
                    $attributes['selected'] = '';
                }
                if (!empty($option[3])) {
                    $attributes = array_merge($attributes, $option[3]);
                }
            } else {
                $content = empty($option['content']) ? '' : $option['content'];
                $attributes = empty($option['attributes']) ? [] : $option['attributes'];
            }

            $item = new Tag('option', $content, $attributes);
        } elseif ($option instanceof Tag && ($option->getTagName() === 'option' || $option->getTagName() === 'optgroup')) {
            $item = $option;
        } else {
            throw new InvalidDatatypeException(
                    'item',
                    $option,
                    ['array'],
                    [Tag::class]
            );
        }

        if ($container instanceof Tag) {
            $container->getContent()->add($item);
        } else {
            $this->content->add($item);
        }
    }

    /**
     * Set the options of the current select element.
     *
     * This method calls <code>$this->add()</code> for every array item.
     *
     * @param   array   $content            Indexed array of assoc arrays.
     * @throws  InvalidDatatypeException
     * @see     add($option)
     */
    public function setContent($content)
    {
        if (is_array($content)) {
            $this->content = new NodeList();
            $hasOptGroup = false;
            $i = 0;

            foreach ($content as $key => $option) {
                if ($i === 0) {
                    $i++;
                    $hasOptGroup = is_string($key);
                }

                if ($hasOptGroup) {
                    $optGrp = new Tag('optgroup', [], ['label' => $key]);

                    foreach ($option as $opt) {
                        $this->add($opt, $optGrp);
                    }
                    $this->add($optGrp);
                } else {
                    $this->add($option);
                }
            }
        } else {
            throw new InvalidDatatypeException(
                    'content',
                    $content,
                    ['array']
            );
        }
    }

}
