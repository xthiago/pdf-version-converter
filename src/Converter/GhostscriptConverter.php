<?php

namespace Xthiago\PDFVersionConverter\Converter;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Process\Process;

class GhostscriptConverter implements ConverterInterface
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var GhostscriptConverterCommand
     */
    protected $command;

    /**
     * Directory where temporary files are stored.
     * @var string
     */
    protected $tmp = '/tmp';

    /**
     * @param GhostscriptConverterCommand $command
     * @param Filesystem $fs
     * @param null|string $tmp
     */
    public function __construct(GhostscriptConverterCommand $command, Filesystem $fs, $tmp = null)
    {
        $this->command = $command;
        $this->fs = $fs;

        if ($tmp)
            $this->$tmp = $tmp;
    }

    /**
     * Generates a unique absolute path for tmp file.
     * @return string absolute path
     */
    protected function generateAbsolutePathOfTmpFile()
    {
        return $this->tmp .'/'. uniqid('pdf_version_changer_') . '.pdf';
    }

    /**
     * Convert the PDF version of given $file to $newVersion.
     * @param string $file absolute path.
     * @param string $newVersion version (1.4, 1.5, 1.6, etc).
     */
    public function convert($file, $newVersion)
    {
        $tmpFile = $this->generateAbsolutePathOfTmpFile();

        $this->command->run($file, $tmpFile, $newVersion);

        if (!$this->fs->exists($tmpFile))
            throw new \RuntimeException("Generated file ({$tmpFile}) not found.");

        $this->fs->copy($tmpFile, $file);
    }
}