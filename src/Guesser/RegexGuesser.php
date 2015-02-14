<?php

namespace Xthiago\PDFVersionConverter\Guesser;

use \RuntimeException;

class RegexGuesser implements GuesserInterface
{
    /**
     * Guess the PDF version of given file.
     *
     * @param string $file aboslute path of PDF
     * @return string version (1.4, 1.5, 1.6).
     * @throws RuntimeException if version can't be guessed.
     */
    public function guess($file)
    {
        $version = $this->guessVersion($file);

        if ($version === null)
            throw new RuntimeException("Can't guess version. {$file} is a PDF file?");

        return $version;
    }

    /**
     * This implementation is not the best, but doesn't require external modules or libs. For now, works fine for me.
     * @author Sameer Borate http://www.codediesel.com/php/read-the-version-of-a-pdf-in-php/
     * @param $filename
     * @return string|null
     */
    protected function guessVersion($filename)
    {
        $fp = @fopen($filename, 'rb');

        if (!$fp) {
            return 0;
        }

        /* Reset file pointer to the start */
        fseek($fp, 0);

        /* Read 20 bytes from the start of the PDF */
        preg_match('/\d\.\d/',fread($fp,20),$match);

        fclose($fp);

        if (isset($match[0]))
            return $match[0];

        return null;
    }
}
