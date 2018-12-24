<?php

use \Salarmehr\Ary;

// require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/Ary.php';

class Test extends PHPUnit\Framework\TestCase

{
    protected $item;

    public function setup()
    {
        $this->item = [];
    }

    public function provider()
    {
        return [
            [['']],
            [['ali', 'reza', 'mohammad']],
            [[1, 2, 3, 4]],
        ];
    }

    /**
     * @param array $originalary array to get
     * @param array $expectedary What we expect to get
     *
     * @dataProvider various
     */

    public function testAll($originalary, $expectedary)
    {
        $ary = ary($originalary);

        $ary->all();

        $this->assertEquals($expectedary, $originalary);

    }

    public function various()
    {
        return [
            ['', ''],
            [null, null],
            [['ali', 'reza', 'mohammad'], ['ali', 'reza', 'mohammad']],
            [['name' => 'ali', 'lastname' => 'reza', 'age' => 30], ['name' => 'ali', 'lastname' => 'reza', 'age' => 30]],
            [(object)['name' => 'ali', 'lastname' => 'reza', 'age' => 30], (object)['name' => 'ali', 'lastname' => 'reza', 'age' => 30]],
            [['ali', 'reza', 'mohammad'], ['ali', 'reza', 'mohammad']],
            [[1, 2, 3, 4], [1, 2, 3, 4]],
            [['x' => 'y'], ['x' => 'y']],
        ];
    }


    public function testGet()
    {
        $ary = new Ary();
        $this->assertEquals($ary[0], null);

        $ary = new Ary(['x' => ['xx' => ['m' => 'xxx']]]);
        $this->assertEquals($ary->get('x.xx.m'), 'xxx');
    }

    public function testAryHelper()
    {
        $a = ary(['x' => 2, 'y' => 22]);
        $this->assertEquals($a['x'], 2);
    }


    /**
     * @dataProvider provider
     */
    public function testCount($objects)
    {
        $ary = new Ary($objects);
        var_dump($objects);
        $expected = count($objects);
        $num = count($ary);
        $this->assertEquals($expected, $num);
    }

    /**
     * @dataProvider various
     */
    public function testGetArrayableItems($original, $expected)
    {
        $ary = new Ary($original);
        $this->invokeMethod($ary, 'getArrayableItems', [$original]);
        $this->assertEquals($expected, $original);
    }

    /**
     * @param object &$ary Instantiated object that we will run method on.
     * @param string $getArrayableItems Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     * @throws ReflectionException
     */
    public function invokeMethod(&$ary, $getArrayableItems, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($ary));
        $method = $reflection->getMethod($getArrayableItems);
        $method->setAccessible(true);
        return $method->invokeArgs($ary, $parameters);
    }

    public function testAssignment()
    {
        $ary = new Ary();
        $ary[] = 3;
        $this->assertEquals($ary[0], 3);
        $this->assertEquals($ary->{0}, 3);
        $this->assertTrue($ary->has(0));
        $ary['x'] = ['z' => 'y'];
        $ary['foo'] = 'bar';
        $this->assertEquals($ary['foo'], 'bar');
        $this->assertEquals($ary->foo, 'bar');
        $this->assertTrue($ary->has('foo'));
        $this->assertEquals($ary['x']['z'], 'y');
        $ary['x']['z'] = 'm';
        $this->assertEquals($ary['x']['z'], 'm');

    }

    /**
     * @dataProvider various
     * @param $original
     */
    public function testJsonSerialize($original)
    {
        $this->assertEquals(json_encode(new Ary($original)), json_encode((array)$original));
    }

    /**
     * @dataProvider various
     */
    public function ary()
    {
        $test = ['x' => ['xx' => 'xxx']];
        $ary = new Ary($test);
        $this->assertEquals(ary($test)->x['xx'], $ary->ary('x')->xx);
        $this->assertEquals(ary(ary($test)->x['xx']), $ary->ary('x')->ary('xx'));
    }

    public function testReplace()
    {
        $c = new Ary(['foo' => 'x', 'bar' => 'y']);
        $this->assertEquals(['foo' => 'f', 'bar' => 'y', 'baz' => 'z'], $c->replace(new Ary(['foo' => 'f', 'baz' => 'z']))->all());
    }

    public function testReplaceAryRecursively()
    {
        $base = ['citrus' => ["orange"], 'berries' => ["blackberry", "raspberry"],];
        $replacements = ['citrus' => ['pineapple'], 'berries' => ['blueberry']];
        $expect = ['citrus' => ['pineapple'], 'berries' => ['blueberry', 'raspberry']];
        $c = new Ary($base);
        $this->assertEquals($expect, $c->replaceRecursively(new Ary($replacements))->all());
    }

    public function testOnly()
    {
        $c = new Ary(['foo' => 'x', 'bar' => 'y']);
        $this->assertEquals(['bar' => 'y'], $c->only(['bar'], true));
        $this->assertEquals(['bar' => 'y'], $c->only(['bar'])->all());
    }

    public function testExcept()
    {
        $c = new Ary(['foo' => 'x', 'bar' => 'y']);
        $this->assertEquals(['foo' => 'x'], $c->except(['bar'], true));
        $this->assertEquals(['foo' => 'x'], $c->except(['bar'])->all());
    }

    public function testOffsetExists()
    {

        $parameters = [4, 5, 2, 0, 1, 3];
        $ary = new Ary($parameters);

        for ($i = 0; $i <= count($ary); $i++) {
            $key = [];
            $key[$i] = $ary->{$i};
            if ($i < count($ary)) {
                $resultTrue = $ary->offsetExists($key[$i]);
                $this->assertTrue($resultTrue);
            } else {
                $resultFalse = $ary->offsetExists($key[$i]);
                $this->assertFalse($resultFalse);
            }
        }
    }

    public function testToObject()
    {

        $name1 = 'mehdi';
        $height1 = 181;

        $name2 = 'ehsan';
        $height2 = 176;

        $persons = [];
        $persons[$name1] = $height1;
        $persons[$name2] = $height2;

        $ary = new Ary($persons);
        $objected = $ary->toObject();


        $this->assertInternalType("string", $name1);
        $this->assertInternalType("array", $persons);
        $this->assertInternalType("object", $ary);
        $this->assertInternalType("object", $objected);

    }
}
