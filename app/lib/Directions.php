<?php
/**
 * Class Directions
 *
 * Walk through input and calculate average
 */

namespace App\Lib;

use App\Entity\iDirections;
use PHPUnit\Runner\Exception;

class Directions implements iDirections
{
    protected $directions = [];

    const WALK = 'walk';
    const TURN = 'turn';
    const START = 'start';

    /**
     * @param array $data
     */
    public function addDirection(array $data)
    {
        $x = $data[0];
        $y = next($data);

        // It's start
        if ($this->nextElementIs($data, self::START)) {
            next($data);
            $angle = (float)next($data);

        } else {
            throw new Exception("Undefined key - Start");
        }

        // Turn and walk
        while ($this->hasNext($data)) {
            if ($this->nextElementIs($data, self::TURN)) {
                next($data);
                $angle += (float)next($data);
            } elseif ($this->nextElementIs($data, self::WALK)) {
                next($data);
                $walk = (float)next($data);
                $this->createCoordinatesByWalk($angle, $x, $y, $walk);
            }
        }

        $direction = new Direction();
        $direction->setX($x);
        $direction->setY($y);

        $this->directions[] = $direction;
    }

    /**
     * @param Direction $avgDirection
     *
     * @return float
     */
    public function getAvgDestination(Direction $avgDirection): float
    {
        $result = $destination = (float)0;

        foreach ($this->directions as $direction) {
            $result = (float)max(
                $destination,
                hypot($avgDirection->getX() - $direction->getX(), $avgDirection->getY() - $direction->getY())
            );
        }

        return $result;
    }

    /**
     * @return Direction
     */
    public function getAvgDirection(): Direction
    {
        $avgDirection = new Direction();
        $avgDirection->setX(0);
        $avgDirection->setY(0);

        $n = count($this->directions);

        foreach ($this->directions as $direction) {
            $avgDirection->setX($avgDirection->getX() + $direction->getX());
            $avgDirection->setY($avgDirection->getY() + $direction->getY());
        }

        $avgDirection->setX($avgDirection->getX() / $n);
        $avgDirection->setY($avgDirection->getY() / $n);

        return $avgDirection;
    }

    public function getDirections(): array
    {
        return $this->directions;
    }

    /**
     * @param $angle
     * @param $x
     * @param $y
     * @param $walk
     */
    private function createCoordinatesByWalk($angle, &$x, &$y, $walk)
    {
        // Cos (Radiant)
        $x += $walk * cos($angle * M_PI / 180);
        $y += $walk * sin($angle * M_PI / 180);
    }

    /**
     * @param array $array
     * @param       $is
     *
     * @return bool
     */
    private function nextElementIs(array $array, $is)
    {
        return (next($array) === $is);
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    private function hasNext(array $array): bool
    {
        if (is_array($array)) {
            return (next($array) === false ? false : true);
        } else {
            return false;
        }
    }
}