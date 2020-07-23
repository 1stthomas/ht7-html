# Ht7 - Html #

## Abstract ##


## How to use it ##

```php
use \Ht7\Html\Tag;

$tag = new Tag('div', ['inner text.'], ['id' => 'ht7-inner-1', 'class' => 'inner']);
echo $tag;
// output: <div class="inner" id="ht7-inner-1">inner text.</div>
```
While there is no way to add attributes directly to `Tag` instance, you need to
gain the attribute list first. After that you can add further attributes to the
attribute list. At the end there is no need to readd the attribute list again,
because it was given by reference.
```php
use \Ht7\Html\Attribute;

$aL = $tag->getAttributes();
$aL->addPlain($name, $value);
// or:
$aL->add((new Attribute($name, $value)));
```
Method chainning is also supported:
```php
$tag->getAttributes()
    ->addPlain($name1, $value1)
    ->addPlain($name2, $value2);
```


## Testing ##
Trigger the unit tests with following CLI command:
```
$ composer test-unit
```
or run the functional tests by:
```
$ composer test-func
```
