<?php

namespace Util;

/**
 * The PathToFileValidator provides check validity of path to file.
 * Also may be checked file existing.
 */
class PathToFileValidator
{
    /**
     * Check path to file valid or not.
     *
     * @param string $pathToFile Path to checked file
     * @param string $extension Extension of checked file
     * @param bool $checkExist Flag for checking file existing
     *
     * @return bool Path to file valid or not
     */
    static function validate(string $pathToFile, string $extension = '', bool $checkExist = false): bool
    {
        $pattern = self::getPathToFilePattern();

        if (!preg_match($pattern, $pathToFile))
            return false;

        if (self::isDir($pathToFile))
            return false;

        if ($extension && ($extension !== pathinfo($pathToFile)['extension']))
            return false;

        if ($checkExist && !(file_exists($pathToFile)))
            return false;

        return true;
    }

    /**
     * Check which operating system used and return her
     * file system path pattern in regular expression.
     *
     * @return string Pattern of file system path
     */
    private static function getPathToFilePattern(): string
    {
        $operationSystemName = PHP_OS;

        if (strtoupper(substr($operationSystemName, 0, 3)) === 'WIN')
            return '/^[a-zA-Z]:\\\\[\\\\\S|\*\S]?.*$/';
        else
            return '/^~{0,1}\/[\/\S|\*\S]?.*$/';
    }

    /**
     * Check path to file is directory or not.
     *
     * @param string $pathToFile Path to checked file
     *
     * @return bool Path to file is directory or not
     */
    public static function isDir(string $pathToFile): bool
    {
        if (!is_dir($pathToFile))
            return false;

        return true;
    }
}