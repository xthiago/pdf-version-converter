<?php

namespace Xthiago\PDFVersionConverter\Converter;

use Symfony\Component\Process\Process;

class GhostscriptConverterCommand
{
    /**
     * @var Filesystem
     */
    protected $baseCommand = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=%s -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile=%s %s';

    public function __construct()
    {
    }

    public function run($originalFile, $newFile, $newVersion)
    {
        $command = sprintf($this->baseCommand, $newVersion, $newFile, $originalFile);

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }
}
