<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

/**
 * Efika test suite functions
 */

/**
 * Returns path of class in src related to test class.
 * Custom path's won't validate! Use DIRECTORY_SEPARATOR instead
 * of '/' or '\'
 * @param $file
 * @param null $testDir usually ./tests/
 * @param null $libraryDir usually ./src/
 * @param string $suffix
 * @param string $ext
 * @return string
 */
function getRelatedLibraryClass($file, $testDir = null, $libraryDir = null, $suffix = 'Test', $ext = '.php')
{
    if ($testDir == null)
        $testDir = 'tests' . DIRECTORY_SEPARATOR;
    if ($libraryDir == null)
        $libraryDir = 'src' . DIRECTORY_SEPARATOR;
    return realpath(
        str_replace(
            array($testDir, $suffix . $ext),
            array($libraryDir, $ext),
            $file
        )
    );
}