<?php

/*
 * This file is part of the PDF Version Converter.
 *
 * (c) Thiago Rodrigues <xthiago@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Xthiago\PDFVersionConverter\Guesser;

use \PHPUnit_Framework_TestCase;

/**
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
class RegexGuesserTest extends PHPUnit_Framework_TestCase
{
    protected static $files = array(
        'text',
        'image.png',
        'v1.0.pdf',
        'v1.1.pdf',
        'v1.2.pdf',
        'v1.3.pdf',
        'v1.4.pdf',
        'v1.5.pdf',
        'v1.6.pdf',
        'v1.7.pdf',
        'v2.0.pdf',
    );

    protected $tmpDir;

    protected $stageDir;

    protected function setUp()
    {
        $this->tmpDir = __DIR__.'/../files/repo/';
        $this->stageDir = __DIR__.'/../files/stage/';

        if (!file_exists($this->stageDir))
            mkdir($this->stageDir);

        foreach(self::$files as $file) {
            if (!copy($this->tmpDir . $file, $this->stageDir . $file))
                throw new \RuntimeException("Can't create test file.");
        }
    }

    protected function tearDown()
    {
        foreach(self::$files as $file) {
            unlink($this->stageDir . $file);
        }
    }

    /**
     * @param string $file
     * @param string $expectedVersion
     *
     * @dataProvider filesProvider
     */
    public function testMustReturnRightVersion($file, $expectedVersion)
    {
        $guesser = new RegexGuesser();
        $version = $guesser->guess($file);

        $this->assertEquals($version, $expectedVersion);
    }

    /**
     * @param string $file
     *
     * @dataProvider invalidFilesProvider
     * @expectedException RuntimeException
     */
    public function testMustThrowException($file)
    {
        $guesser = new RegexGuesser();
        $version = $guesser->guess($file);
    }

    /**
     * @return array
     */
    public static function filesProvider()
    {
        return array(
            // file, current version
            //array(__DIR__ . '/../files/stage/v1.0.pdf', '1.0'),
            array(__DIR__ . '/../files/stage/v1.1.pdf', '1.1'),
            array(__DIR__ . '/../files/stage/v1.2.pdf', '1.2'),
            array(__DIR__ . '/../files/stage/v1.3.pdf', '1.3'),
            array(__DIR__ . '/../files/stage/v1.4.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.5.pdf', '1.5'),
            array(__DIR__ . '/../files/stage/v1.6.pdf', '1.6'),
            array(__DIR__ . '/../files/stage/v1.7.pdf', '1.7'),
            //array(__DIR__ . '/../files/stage/v2.0.pdf', '2.0'),
        );
    }

    /**
     * @return array
     */
    public static function invalidFilesProvider()
    {
        $stageDir = __DIR__.'/../files/stage/';

        return array(
            // file
            array($stageDir . 'text'),
            array($stageDir . 'image.png'),
        );
    }
}