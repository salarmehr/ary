Ary 
===

Ary provides unified interface for ary/object. Really handy to store:
 
 - arrays/objects those items/properties varies conditionally.
 - storing registry, system configuration, env variables, etc.
 - when you perform a chain of action on an array/object

Features
---------
- You can access array items using `->` or `['']` syntax. 
- You will get `null` if an index does not exist. 
- Some of really handy methods: `merge`, `only`, `search`, `toObject`, ... 
- `Ary` extends `\ArrayObject` class so all of its methods are available (https://www.php.net/manual/en/class.arrayobject.php#arrayobject.constants.array-as-props)

Examples
--------
```php
// Instantiation
$ary = new Ary();
// or simply (if you have included the helper in composer.json
$ary = ary();

//Initialization
$ary = ary([2, 4, 6, 8]); 
$ary = ary(['x' => 'foo', 'y' => 'bar']);

//Assignment
$ary->newItem=20; //or
$ary['newItem']=20;

//Retrieval
$foo = $ary->x; //or
$foo = $ary['x'];
$ary->toArray(); // returns the simple php array;

// works as regular arrays
count($ary); //returns 3
unset($ary[0]); 
json_encode($ary);
```

Installation
============
```bash
composer require salarmehr/ary
```    
    
Licence
=======
MIT
