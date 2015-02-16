<?php

/*
 * This file is part of the PDF Version Converter.
 *
 * (c) Thiago Rodrigues <xthiago@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Xthiago\PDFVersionConverter\Converter;

use \PHPUnit_Framework_TestCase;
use Xthiago\PDFVersionConverter\Guesser\RegexGuesser;

/**
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
class GhostscriptConverterCommandTest extends PHPUnit_Framework_TestCase
{
    protected $tmp;

    protected $files = array(
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

    protected function setUp()
    {
        $this->tmp = __DIR__.'/../files/stage/';

        if (!file_exists($this->tmp))
            mkdir($this->tmp);

        $this->copyFilesToStageArea();
    }

    protected function copyFilesToStageArea()
    {
        foreach($this->files as $file) {
            if (!copy(__DIR__.'/../files/repo/'. $file, $this->tmp . $file))
                throw new \RuntimeException("Can't create test file.");
        }
    }

    protected function tearDown()
    {
        foreach($this->files as $file) {
            unlink($this->tmp . $file);
        }
    }

    /**
     * @param string $file
     * @param $newVersion
     *
     * @dataProvider filesProvider
     */
    public function testMustConvertPDFVersionWithSuccess($file, $newVersion)
    {
        $tmpFile = $this->tmp .'/'. uniqid('pdf_version_changer_test_') . '.pdf';

        $command = new GhostscriptConverterCommand();
        $command->run(
            $file,
            $tmpFile,
            $newVersion
        );

        $guesser = new RegexGuesser();
        $version = $guesser->guess($tmpFile);

        $this->assertEquals($version, $newVersion);
    }

    /**
     * @param string $invalidFile
     * @param $newVersion
     *
     * @dataProvider invalidFilesProvider
     * @expectedException RuntimeException
     */
    public function testMustThrowException($invalidFile, $newVersion)
    {
        $tmpFile = $this->tmp .'/'. uniqid('pdf_version_changer_test_') . '.pdf';

        $command = new GhostscriptConverterCommand();
        $command->run(
            $invalidFile,
            $tmpFile,
            $newVersion
        );

        $guesser = new RegexGuesser();
        $version = $guesser->guess($tmpFile);

        $this->assertEquals($version, $newVersion);
    }

    /**
     * @return array
     */
    public static function filesProvider()
    {
        return array(
            // file, new version
            array(__DIR__ . '/../files/stage/v1.1.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.2.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.3.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.4.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.5.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.6.pdf', '1.4'),
            array(__DIR__ . '/../files/stage/v1.7.pdf', '1.4'),
        );
    }

    /**
     * @return array
     */
    public static function invalidFilesProvider()
    {
        return array(
            // file, new version
            array(__DIR__.'/../files/stage/text', '1.4'),
            array(__DIR__.'/../files/stage/image.png', '1.5'),
            array(__DIR__.'/../files/stage/dont-exists.pdf', '1.5'),
        );
    }
}