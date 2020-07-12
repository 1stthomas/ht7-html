# Ht7 - Html #

## Abstract ##


## How to use it ##

```php
use \Ht7\Html\Tag;

$tag = new Tag('div', ['inner text.'], ['class' => 'inner']);
echo $tag;
// output: <div class="inner">inner text.</div>
```
While there is no way to add attributes directly to `Tag` instance, you need to
gain the attribute list first. After that you can add further attributes to the
attribute list. At the end there is no need to read the attribute list again,
because it was given by reference.
```php
use \Ht7\Html\Attribute;

$aL = $tag->getAttributes();
$aL->addPlain($name1, $value1);
// or:
$aL->add((new Attribute($name2, $value2)));
```

## Testing ##
To test the unit tests just use the composer command:
```
$ composer test-unit
```
or run the functional tests by:
```
$ composer test-func
```
