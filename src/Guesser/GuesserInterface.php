<?php

namespace Xthiago\PDFVersionConverter\Guesser;

/**
 * Classes that implements this interface can guess the PDF version of given file.
 *
 * @package Xthiago\PDFVersionConverter\Guesser
 */
interface GuesserInterface
{
    /**
     * Guess the PDF version of given file.
     *
     * @param string $file
     * @return string version (1.4, 1.5, 1.6) or null.
     */
    public function guess($file);
}
