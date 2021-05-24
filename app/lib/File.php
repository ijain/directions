<?php
/**
 * Class File
 *
 * File path and file manipulations
 */

namespace App\Lib;

class File
{
    /**
     * @var string $inputFile
     */
    protected $inputFile;

    /**
     * @var string $outputFile
     */
    protected $outputFile;

    /**
     * @return string
     */
    public function getInputFile(): string
    {
        return $this->inputFile;
    }

    /**
     * @param string $inputFile
     */
    public function setInputFile(string $inputFile)
    {
        $this->inputFile = $inputFile;
    }

    /**
     * @return string
     */
    public function getOutputFile(): string
    {
        return $this->outputFile;
    }

    /**
     * @param string $outputFile
     */
    public function setOutputFile(string $outputFile)
    {
        $this->outputFile = $outputFile;
    }

    /**
     * Delete file
     */
    public function delete($file)
    {
        if (file_exists($file)) {
            unlink($file);
        }
    }
}