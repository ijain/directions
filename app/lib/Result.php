<?php
/**
 * Class Result
 *
 * Processing data and writing results
 */

namespace App\Lib;

use App\Entity\iResult;
use PHPUnit\Runner\Exception;

class Result implements iResult
{
    /**
     * @var Directions $directions
     */
    protected $directions;

    /**
     * @var File $file
     */
    protected $file;

    public function __construct(File $file, Directions $directions)
    {
        $this->directions = $directions;
        $this->file = $file;
    }

    /**
     * Reading input
     *
     * @return array
     * @throws Exception
     */
    public function getData(): array
    {
        try {
            if (file_exists($this->file->getInputFile())) {
                $lines = file($this->file->getInputFile());
                $result = $this->process($lines);
            } else {
                throw new Exception('Input file does not exists');
            }
        } catch (Exception $e) {
            die($e->getMessage());
        }

        return $result;
    }

    /**
     * @param array $data
     */
    public function write(array $data)
    {
        foreach ($data as $item) {
            $this->writeLine($this->format($item));
        }
    }

    /**
     * Write output to file
     *
     * @param string $data
     *
     * @throws Exception
     */
    protected function writeLine(string $data)
    {
        try {
            file_put_contents($this->file->getOutputFile(), $data, FILE_APPEND);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function format(array $item)
    {
        return sprintf(
            '%.4F %.4F %.5F',
            $item['avgDirection']->getX(),
            $item['avgDirection']->getY(),
            $item['avgDestination']
            ) . "\n";
    }

    /**
     * @param array $lines
     *
     * @return array
     */
    protected function process($lines)
    {
        $result = [];
        $n = 0;

        foreach ($lines as $line) {
            $data = explode(' ', $line);

            if ((int)count($data) === 1) {
                $this->directions = new $this->directions;
                $n++;
                continue;
            } else {
                $this->directions->addDirection($data);
                $avgDirection = $this->directions->getAvgDirection();
                $result[$n]['avgDirection'] = $avgDirection;
                $result[$n]['avgDestination'] = $this->directions->getAvgDestination($avgDirection);
            }
        }

        return $result;
    }
}