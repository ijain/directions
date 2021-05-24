<?php
/**
 * Testing Directions
 */

use PHPUnit\Framework\TestCase;
use App\Lib\Directions;
use App\Lib\Direction;

class DirectionsTest extends TestCase
{
    public function testDirection()
    {
        $direction = new Direction();
        $direction->setX(40);
        $direction->setY(70);

        $this->assertInternalType('float', $direction->getX());
        $this->assertInternalType('float', $direction->getY());

        $direction->setX(10);
        $direction->setY(20);

        $this->assertEquals(10, $direction->getX());
        $this->assertEquals(20, $direction->getY());
    }

    public function testDirectionsAddDirection()
    {
        $objDirections = new Directions();

        $data = [87.342, 34.30, 'start', 0, 'walk', 10.0];
        $objDirections->addDirection($data);

        $arrDirections = $objDirections->getDirections();
        $direction = $arrDirections[0];

        $this->assertObjectHasAttribute('x', $direction);
        $this->assertObjectHasAttribute('y', $direction);

        $this->assertEquals(97.342, $direction->getX());
        $this->assertEquals(34.30, $direction->getY());
    }

    public function testGetAvgDirection()
    {
        $objDirections = $this->__addDirections();
        $avgDirection = $objDirections->getAvgDirection();

        $this->assertObjectHasAttribute('x', $avgDirection);
        $this->assertObjectHasAttribute('y', $avgDirection);

        $this->assertEquals(97.1547, number_format($avgDirection->getX(), 4));
        $this->assertEquals(40.2334, number_format($avgDirection->getY(), 4));
    }

    public function testGetAvgDestination()
    {
        $objDirections = $this->__addDirections();
        $avgDirection = $objDirections->getAvgDirection();
        $avgDestination = $objDirections->getAvgDestination($avgDirection);

        $this->assertEquals(7.63097, number_format($avgDestination, 5));
    }

    public function testGetDirections()
    {
        $objDirections = $this->__addDirections();
        $arrDirections = $objDirections->getDirections();

        $this->assertInternalType('array', $arrDirections);
        $this->assertNotEmpty($arrDirections);
        $this->assertCount(3, $arrDirections);
    }

    /**
     * Testing Private method createCoordinatesByWalk()
     */
    public function testCreateCoordinatesByWalk()
    {
        $directions = new Directions();

        $angle = -45;
        $x = 2.6762;
        $y = 75.2811;
        $walk = (float)40;

        $this->__invokeMethod(
            $directions,
            'createCoordinatesByWalk',
            ['angle' => $angle, &$x, &$y, 'walk' => $walk]
        );

        $this->assertInternalType('float', $x);
        $this->assertInternalType('float', $y);

        $this->assertEquals(30.9605, number_format($x, 4));
        $this->assertEquals(46.9968, number_format($y, 4));
    }

    public function testNextElementIs()
    {
        $directions = new Directions();
        $data = [58.518, 93.508, 'start', 270, 'walk', 50, 'turn', 90];
        $actions = [Directions::START, Directions::WALK, Directions::TURN];

        next($data);

        foreach ($actions as $i => $action) {
            $is = $action;

            $element = $this->__invokeMethod(
                $directions,
                'nextElementIs',
                [$data, $is]
            );

            $this->assertTrue($element);

            if ($i < (count($actions) - 1)) {
                next($data);
                next($data);
            }
        }
    }

    public function testHasNext()
    {
        $directions = new Directions();
        $data = [58.518, 93.508, 'start'];

        next($data);

        $hasElement = $this->__invokeMethod(
            $directions,
            'hasNext',
            [$data]
        );

        $this->assertTrue($hasElement);

        next($data);

        $hasElement = $this->__invokeMethod(
            $directions,
            'hasNext',
            [$data]
        );

        $this->assertFalse($hasElement);
    }

    /**
     * For testing private and protected methods
     *
     * @param       $object
     * @param       $methodName
     * @param array $parameters
     *
     * @return mixed
     */
    protected function __invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Fill object with data
     *
     * @return Directions
     */
    protected function __addDirections()
    {
        $objDirections = new Directions();

        $data = [87.342, 34.30, 'start', 0, 'walk', 10.0];
        $objDirections->addDirection($data);

        $data = [2.6762, 75.2811, 'start', -45.0, 'walk', 40, 'turn', 40.0, 'walk', 60];
        $objDirections->addDirection($data);

        $data = [58.518, 93.508, 'start', 270, 'walk', 50, 'turn', 90,'walk', 40, 'turn', 13, 'walk', 5];
        $objDirections->addDirection($data);

        return $objDirections;
    }
}