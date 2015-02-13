<?php

namespace Xthiago\PDFVersionConverter\Guesser;

/**
 * Classes that implements this interface can guess the PDF version of given file.
 *
 * @package Xthiago\PDFVersionConverter\Guesser
 */
interface GuesserInterface
{
    public function guess($file);
}