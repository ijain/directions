<?php
/**
 * Testing Results
 */

use PHPUnit\Framework\TestCase;
use App\Lib\Result;
use App\Lib\Directions;
use App\Lib\File;
use App\Lib\Direction;

class ResultTest extends TestCase
{
    protected $file;

    protected $directions;

    protected $result;

    public function setUp()
    {
        $this->file = new File();
        $this->file->setOutputFile('./tests/tmp/testOutput.txt');
        $this->file->setInputFile('./tests/tmp/testInput.txt');

        $this->directions = new Directions();
        $this->result = new Result($this->file, $this->directions);
    }

    public function tearDown()
    {
        unset($this->file);
        unset($this->directions);
        unset($this->result);
    }

    /**
     * Test setup objects
     */
    public function testSetUp()
    {
        $this->assertDirectoryIsWritable('./tests/tmp', 'Directory is not writable');
        $this->assertFileExists($this->file->getInputFile());
        $this->assertInstanceOf(Directions::class, $this->directions);
        $this->assertInstanceOf(Result::class, $this->result);
    }

    /**
     * Test result data
     */
    public  function testGetData()
    {
        $data = $this->result->getData();

        $this->assertInternalType('array', $data);
        $this->assertNotEmpty($data);
        $this->assertTrue(count($data) === 2);
    }

    /**
     * Test output writing to file
     */
    public function testWrite()
    {
        //delete old, if with the same name
        $this->file->delete($this->file->getOutputFile());

        $data = $this->result->getData();
        $this->__invokeMethod($this->result, 'write', [$data]);

        $this->assertFileExists($this->file->getOutputFile());
        $this->assertStringNotEqualsFile($this->file->getOutputFile(), '');
    }

    public  function testWriteLine()
    {
        $this->file->setOutputFile('./tests/tmp/testOutputOneLine.txt');

        //delete old, if with the same name
        $this->file->delete($this->file->getOutputFile());

        $data = '97.1547 40.2334 7.63097';
        $this->__invokeMethod($this->result, 'writeLine', [$data]);

        $this->assertFileExists($this->file->getOutputFile());
        $this->assertStringNotEqualsFile($this->file->getOutputFile(), '');

        $lines = file($this->file->getOutputFile());
        $this->assertTrue(count($lines) === 1);
    }

    /**
     * Test data formatting
     */
    public function testFormat()
    {
        $direction = new Direction();
        $direction->setX(97.154705757);
        $direction->setY(40.233406575);

        $data = ['avgDirection' => $direction, 'avgDestination' => 7.6309707575];
        $fromattedString = $this->__invokeMethod($this->result, 'format', [$data]);

        $this->assertEquals("97.1547 40.2334 7.63097\n", $fromattedString);
    }

    /**
     * Test data processing
     */
    public function testProcess()
    {
        $lines = file($this->file->getInputFile());
        $arrResult = $this->__invokeMethod($this->result, 'process', [$lines]);

        $this->assertInternalType('array', $arrResult);
        $this->assertNotEmpty($arrResult);
        $this->assertTrue(count($arrResult) === 2);
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
}