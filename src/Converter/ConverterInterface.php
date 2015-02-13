<?php

namespace Xthiago\PDFVersionConverter\Converter;

interface ConverterInterface
{
    /**
     * Change PDF version of given $file to $newVersion.
     * @param string $file absolute path.
     * @param string $newVersion version (1.4, 1.5, 1.6, etc).
     */
    public function convert($file, $newVersion);
}