<?php

namespace Micro\Frameworks\Filesystem;

/**
 * Utility class for file sizes
 */
class Size
{
    /**
     * Returns the size in bytes from the given file
     *
     * @param string $filename
     *
     * @return string
     */
    public static function fromFile($filename)
    {
        return filesize($filename);
    }
}
