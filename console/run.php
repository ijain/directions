<?php
/**
 * Run Different Directions
 */

require 'vendor/autoload.php';

use App\Lib\File;
use App\Lib\Result;
use App\Lib\Directions;

if (!isset($argv[1])) {
    die('Input file is not specified');
}
if (!isset($argv[2])) {
    die('Output file is not specified');
}

$fileIn = $argv[1];
$fileOut = $argv[2];

$file = new File();

$file->setInputFile($fileIn);
$file->setOutputFile($fileOut);

//delete old output file, if the same path
$file->delete($file->getOutputFile());

$result = new Result($file, new Directions());
$data = $result->getData();
$result->write($data);
