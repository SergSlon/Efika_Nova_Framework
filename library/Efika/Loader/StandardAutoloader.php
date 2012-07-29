<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Loader;

require_once 'SplLoader.php';

class StandardAutoloader implements SplLoader
{

    const NS_SEPERATOR = '\\';
    const PREFIX_SEPERATOR = '_';
    const TYPE_NAMESPACE = 'namespaces';
    const TYPE_PREFIX = 'prefixes';

    protected $namespaces = [];
    protected $prefixes = [];
    protected $classMap = [];

    /**
     * Register autoloader to spl_autoloader_stack
     * @return mixed
     */
    public function register()
    {
        spl_autoload_register(array($this, 'autoload'));
    }

    /**
     * autoload a class
     * @param $class
     * @return mixed
     */
    public function autoload($class)
    {
        if (strpos($class, static::NS_SEPERATOR) !== false) {
            $load = $this->loadClass($class, static::TYPE_NAMESPACE);
            echo '(Marco)' . __FILE__ . ' (' . __LINE__ . ')' . "\n";
            var_dump($load);

            return $this->loadClass($class, static::TYPE_NAMESPACE);
        }

        if (strpos($class, static::PREFIX_SEPERATOR) !== false) {
            return $this->loadClass($class, static::TYPE_PREFIX);
        }
    }

    /**
     * set a bunch of namespace/dir pair
     * @param $namespaces
     * @return void
     */
    public function setNamespaces($namespaces)
    {
        $this->namespaces = $namespaces;
    }

    /**
     * register a namespace/dir pair
     * @param $namespace
     * @param $dir
     */
    public function setNamespace($namespace, $dir)
    {
        $this->namespaces[$namespace] = $this->normalizeDirectory($dir);
    }

    /**
     * return all namespace/prefix pairs
     * @return array
     */
    public function getNamespaces()
    {
        return $this->namespaces;
    }

    /**
     * set a bunch of prefix/dir pair
     * @param $prefixes
     */
    public function setPrefixes($prefixes)
    {
        $this->prefixes = $prefixes;
    }

    /**
     * register a prefix/dir pair
     * @param $prefix
     * @param $dir
     */
    public function setPrefix($prefix, $dir)
    {
        $this->prefixes[$prefix] = $this->normalizeDirectory($dir);
    }

    /**
     * return all prefixes/dir pairs
     * @return array
     */
    public function getPrefixes()
    {
        return $this->prefixes;
    }

    /**
     * create a valid dir
     * @param $dir
     * @return mixed
     */
    protected function normalizeDirectory($dir)
    {
        $last = $dir[strlen($dir) - 1];
        $dirSeperators = ['/', '\\'];
        if (!in_array($last, $dirSeperators)) {
            $dir .= DIRECTORY_SEPARATOR;
        }

        return str_replace($dirSeperators, DIRECTORY_SEPARATOR, $dir);
    }

    protected function loadClass($className, $type)
    {

        foreach ($this->$type as $conversion => $path) {

            if (strpos($className, $conversion) == 0) {
                $trimmedClass = substr($className, strlen($conversion));

                $filename = $this->transformClassNameToFilename($trimmedClass, $path);
                echo '(Marco)' . __FILE__ .' (' . __LINE__ . ')'."\n";
                var_dump(stream_resolve_include_path($filename));
                if (stream_resolve_include_path($filename)) {
                    return require_once $filename;
                }

                return false;
            }
        }


    }

    /**
     * Transform the class name to a filename
     *
     * @param string $class
     * @param string $directory
     * @return string
     */
    protected function transformClassNameToFilename($class, $directory)
    {
        // $class may contain a namespace portion, in which case we need
        // to preserve any underscores in that portion.
        $matches = array();
        preg_match('/(?P<namespace>.+\\\)?(?P<class>[^\\\]+$)/', $class, $matches);

        $class = (isset($matches['class'])) ? $matches['class'] : '';
        $namespace = (isset($matches['namespace'])) ? $matches['namespace'] : '';

        return $directory
            . str_replace(static::NS_SEPERATOR, '/', $namespace)
            . str_replace(static::PREFIX_SEPERATOR, '/', $class)
            . '.php';
    }
}
