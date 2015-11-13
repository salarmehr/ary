Ary 
===

Are you tired from casting objects and arrays to each other or are bored using is `isset`? Don't do those anymore! 
Ary is a light class/function that makes accessing array items more convenient.   

1. You can access array items using `->` or `['']` syntax.
2. You will get `null` if an index does not exists (instead of a nasty notification!)
3. You can specify a default value for missing indexes.
4. You can set/get a value within a deeply nested array using "dot" notation.
5. A bunch of really handy method: `merge`,`only`,`search`,`toObject`, ... 

Examples
--------
~~~~~
// Instantiation
$ary = new Ary();
// or simply
$ary = ary();

//Initialization
$ary = ary(2, 4, 6, 8); // or
$ary = ary([2, 4, 6, 8]); 
$ary = ary(['x' => 'foo', 'y' => 'bar']);

//Assignment;
$ary->newItem=20; //or
$ary['newItem']=20;

//Retrieval
$foo = $ary->x; //or
$foo = $ary['x'];
$missed = $ary->get('missed', 'Default value');
$ary->all(); // returns the simple php array;

// behave similar to regular arrays
count($ary); //returns 3
unset($ary[0]); 
json_encode($ary);

// deep assignment/retrieval
$ary = ary(['products' => ['desk' => ['price' => 100]]]);
$value = $ary['products.desk.price']; //100
$ary['production.table.weight']=200; 

~~~~~~

Installation
============
Simply, `include '__DIR__.'/src/helper.php';`

or using Composer:
    
    composer require salarmehr/ary
    
* The class (`Ary()`) requires PHP 5.4 or newer.
* The helper function (`ary()`) requires PHP 5.6 or newer. 
    
Licence
=======
MIT