Ary 
===
Are you tired from casting objects and array to each other? Ary is a light class/function that makes accessing array items more convenient.  

1. You can access array items using `->` or `[]` syntax.
2. You will get `null` if an index does not exists. 
3. You can specify a default value for a missing index. 

* The class (`Ary()`) requires PHP 5.4 or newer.
* The helper function (`ary()`) requires PHP 5.6 or newer. 

~~~~~
// instantiation
$ary = new Ary();
// or simply
$ary = ary();

//setting and getting array items.

$ary = ary(2, 4, 6, 8); //or
$ary = ary([2, 4, 6, 8]); //or

$ary = ary(['x' => 'foo', 'y' => 'bar']);
$foo = $ary->foo; //or
$foo = $ary['foo'];

$missed = $ary->get('missed', 'Default value');

$ary->newItem=20; //or
$ary['newItem']=20;

count($ary); //returns 3
$ary->all(); // returns simple php array;
