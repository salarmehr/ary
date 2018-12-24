Ary 
===

Ary provides unified interface for ary/object. Really handy to store:
 
 - arrays/objects those their items/properties varies conditionally.
 - storing registry, system configuration, env variables, etc.
 - when you perform a chain of action on an array/object
 
 It's super cool and after a while you can not code in php without it.

Features
---------
1. You can access array items using `->` or `['']` syntax.
2. You will get `null` if an index does not exists (instead of a nasty notification!)
3. You can specify a default value for missing indexes.
4. You can set/get a value within a deeply nested array using "dot" notation.
5. A bunch of really handy methods: `merge`, `only`, `search`, `toObject`, ... 
6. Now, `Ary` extends `Illuminate\Support\Collection` class so all of its methods are available (https://laravel.com/docs/master/eloquent-collections)

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

//Assignment
$ary->newItem=20; //or
$ary['newItem']=20;

//Retrieval
$foo = $ary->x; //or
$foo = $ary['x'];
$missed = $ary->get('missed', 'Default value');
$ary->all(); // returns the simple php array;

// works as regular arrays
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
Simply `include '__DIR__.'/src/helper.php';`

or use Composer:
    
    composer require salarmehr/ary
    
* The `Ary()` requires PHP 5.4 or above.
* The `ary()` helper function requires PHP 5.6 or above. 
    
Licence
=======
MIT
