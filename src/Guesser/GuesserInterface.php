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

/**
 * Classes that implements this interface can guess the PDF version of given file.
 *
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
interface GuesserInterface
{
    /**
     * Guess the PDF version of given file.
     *
     * @param string $file
     * @return string version (1.4, 1.5, 1.6) or null.
     * @throws \RuntimeException if version can't be guessed.
     */
    public function guess($file);
}
