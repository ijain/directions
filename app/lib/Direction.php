<?php
/**
 * Class Direction
 *
 * Direction location
 */

namespace App\Lib;

class Direction
{
    /**
     * @var $x float
     */
    protected $x;

    /**
     * @var $y float
     */
    protected $y;

    /**
     * @param float $x
     */
    public function setX(float $x)
    {
        $this->x = (float)$x;
    }

    /**
     * @return float
     */
    public function getX(): float
    {
        return (float)$this->x;
    }

    /**
     * @param float $y
     */
    public function setY(float $y)
    {
        $this->y = (float)$y;
    }

    /**
     * @return float
     */
    public function getY(): float
    {
        return (float)$this->y;
    }
}