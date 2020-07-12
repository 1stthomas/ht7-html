# Ht7 - Html #

## Abstract ##


## How to use it ##

```php
use \Ht7\Html\Tag;

$tag = new Tag('div', ['inner text'], ['class' => 'inner']);
echo $tag;
```
While there is no way to add attributes directly to `Tag` instance, you need to
gain the attribute list first. After that you can add further attributes to the
attribute list. At the end there is no need to readd the attribute list again,
because it was given by reference.
```
$aL = $tag->getAttributes();
$aL->addPlain($name, $value);
// Another way:
// $aL->add((new \Ht7\Html\Attribute($name, $value)));
```