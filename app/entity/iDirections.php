<?php
/**
 * Directions Interface. Calculations.
 */

namespace App\Entity;

use App\Lib\Direction;

interface iDirections
{
    /**
     *
     * @param string $direction
     *
     */
    public function addDirection(array $directions);

    /**
     * @param Direction $avgDirection
     *
     * @return float
     */
    public function getAvgDestination(Direction $avgDirection): float;

    /**
     * @return Direction
     */
    public function getAvgDirection(): Direction;

    /**
     * @return array
     */
    public function getDirections(): array;
}