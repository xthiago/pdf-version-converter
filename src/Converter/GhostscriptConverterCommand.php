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

use Symfony\Component\Process\Process;

/**
 * Encapsulates the knowledge about gs command.
 *
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
class GhostscriptConverterCommand
{
    /**
     * @var Filesystem
     */
    protected $baseCommand = '%s -sDEVICE=pdfwrite -dCompatibilityLevel=%s -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile=%s %s';

    /**
     * @var string
     */
    protected $executable;

    public function __construct()
    {
    }

    public function setExecutable($executable)
    {
        $this->executable = $executable;
    }

    private function getExecutable()
    {
        return $this->executable ?: $this->getExecutableByOS();
    }

    private function getExecutableByOS()
    {
        $os = PHP_OS;

        $executablesByOS = [
            'WINNT' => [
                '32' => 'gswin32c',
                '64' => 'gswin64c'
            ],
            'LINUX' => [
                'i686' => 'gs',
                'x86_64' => 'gs'
            ],
        ];

        if ($os === 'WINNT') {
            $out = [];
            exec("wmic cpu get DataWidth", $out);
            $bits = strstr(implode("", $out), "64") ? 64 : 32;
            $architecture = $bits;
        } else {
            $architecture = shell_exec('arch');
        }

        $executable = $executablesByOS[$os][$architecture];
        
        if (!$this->checkExecutableExists($executable)) {
            throw new Exception("Cannot determine GhostScript exe");
        }

        return $executable;        
    }

    private function checkExecutableExists($executable)
    {
        if (!shell_exec('which ' . $executable)) {
            return false;
        }

        return true;
    }

    public function run($originalFile, $newFile, $newVersion)
    {
        $command = sprintf($this->baseCommand, $this->getExecutable(), $newVersion, $newFile, $originalFile);

        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }
    }
}
