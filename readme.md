Ary 
===
Ary makes PHP array syntax more flexible in addiction to some really necessary utility methods.

1. You can access array items using `->` or `[]` syntax.
2. You will get `null` if an index does not exists. 
3. You can specify a default value for a missing index. 


    // instantiation
    $ary= new Ary();
    // or simply
    $ary=ary();
    
    // filling ary
    
    $ary=ary(2,4,6,8); //or
    $ary=ary([2,4,6,8]); //or
    
    $ary=ary(['x'=>'foo','y'=>'bar]);
    $foo= $ary->foo; //or
    $foo= $ary['foo']; 
    
    $missed=$ary->get('missed','Default value');
    
    count($ary); //return 3
    $ary->all(); // return simple php array;