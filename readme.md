Ary 
===
Are you tired from casting objects and arrays to each other? Don't do that anymore! Ary is a light class/function that makes accessing array items more convenient.  

1. You can access array items using `->` or `['']` syntax.
2. You will get `null` if an index does not exists (instead of a nasty notification!)
3. You can specify a default value for missing indexes.
4. It extends PHP ArrayObject class. So you can use its methods. 

* The class (`Ary()`) requires PHP 5.4 or newer.
* The helper function (`ary()`) requires PHP 5.6 or newer. 

~~~~~
// instantiation
$ary = new Ary();
// or simply
$ary = ary();

//setting and getting array items.

$ary = ary(2, 4, 6, 8); //or
$ary = ary([2, 4, 6, 8]); 

$ary = ary(['x' => 'foo', 'y' => 'bar']);
$foo = $ary->x; //or
$foo = $ary['x'];

$missed = $ary->get('missed', 'Default value');

$ary->newItem=20; //or
$ary['newItem']=20;

count($ary); //returns 3
$ary->all(); // returns simple php array;
~~~~~~


Installation
============

    composer require salarmehr/ary
    
    
