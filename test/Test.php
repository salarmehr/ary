<?php

/**
 * Created by PhpStorm.
 * User: samaneh
 * Date: 2015/10/15
 * Time: 11:12 AM
 */
use salarmehr\ary;

require '..\src\Ary.php';

class Test extends PHPUnit_Framework_TestCase
{
    protected $item;

    public function setup()
    {
        $this->item = [];
    }

    public function provider()
    {
        return array(
            array(''),
            array(['ali', 'reza', 'mohammad']),
            array([1, 2, 3, 4]),
        );
    }

    /**
     * @param array $originalary array to get
     * @param array $expectedary What we expect to get
     *
     * @dataProvider various
     */

    public function testAll($originalary, $expectedary)
    {
        $ary = new Ary();
        $ary->all($originalary);
        $this->assertEquals($expectedary, $originalary);
    }

    public function various()
    {
        return array(
            array('', ''),
            array(['ali', 'reza', 'mohammad'], ['ali', 'reza', 'mohammad']),
            array(['name' => 'ali', 'lastname' => 'reza', 'age' => 30], ['name' => 'ali', 'lastname' => 'reza', 'age' => 30]),
            array((object)['name' => 'ali', 'lastname' => 'reza', 'age' => 30], (object)['name' => 'ali', 'lastname' => 'reza', 'age' => 30]),
            array(['ali', 'reza', 'mohammad'], ['ali', 'reza', 'mohammad']),
            array([1, 2, 3, 4], [1, 2, 3, 4]),
        );
    }


    public function testGet()
    {
        $ary = new Ary();
        $this->assertEquals($ary[0], null);
    }


    /**
     * @dataProvider Provider
     */
    public function testCount($objects)
    {
        $ary = new Ary($objects);
        $result = count($objects);
        $num = count($ary);
        $this->assertEquals($result, $num);
    }

    /**
     * @dataProvider various
     */
    public function testGetArrayableItems($original, $expected)
    {
        $ary = new Ary($original);
        $result = $this->invokeMethod($ary, 'getArrayableItems', array($original));
        $this->assertEquals($expected, $original);
    }

    /**
     * @param object &$ary Instantiated object that we will run method on.
     * @param string $getArrayableItems Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$ary, $getArrayableItems, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($ary));
        $method = $reflection->getMethod($getArrayableItems);
        $method->setAccessible(true);
        return $method->invokeArgs($ary, $parameters);
    }

//    public function testOffsetExists()
//    {
//        $parameters = array(7,8,9,4);
//        $ary= new Ary($parameters);
//        $key= $ary[1];
//        $result=$ary->offsetExists($key);
//        $this->assert($result);
//    }
}
