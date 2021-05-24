<?php
/**
 * File Interface. For input/output
 */

namespace App\Entity;


use PHPUnit\Runner\Exception;

interface iResult
{
    /**
     * Reading input
     *
     * @return array
     * @throws Exception
     */
    public function getData(): array;

    /**
     * Write result data to file
     *
     * @param array $data
     *
     */
    public function write(array $data);
}