<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Loader;

require_once 'SplLoader.php';

/**
 * PSR-0 compliant autoloader
 * Example:
 *  (new StandardAutoloader)
 *  ->setNamespace('Efika',__DIR__ . '/../library/Efika')
 *  ->setNamespace('Zend',__DIR__ . '/../library/Zend')
 *  ->register();
 *
 *  OR
 *
 *  (new StandardAutoloader)
 *  ->setNamespaces(
 *      [
 *          'Efika' => __DIR__ . '/../library/Efika',
 *          'Zend' => __DIR__ . '/../library/Zend'
 *      ]
 *  )
 *  ->register();
 */
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
            return $this->loadClass($class, static::TYPE_NAMESPACE);
        }

        if (strpos($class, static::PREFIX_SEPERATOR) !== false) {
            return $this->loadClass($class, static::TYPE_PREFIX);
        }
    }

    /**
     * set a bunch of namespace/dir pair
     * @param $namespaces
     * @return \Efika\Loader\StandardAutoloader
     * @throws Exception
     */
    public function setNamespaces($namespaces)
    {

        if (!is_array($namespaces) || $namespaces instanceof \Traversable) {
            require_once 'Exception.php';
            throw new Exception('Invalid Argument. $prefix must be an array or traversable!');
            exit(1);
        }

        foreach ($namespaces as $namespace => $dir) {
            $this->setNamespace($namespace, $dir);
        }

        return $this;
    }

    /**
     * register a namespace/dir pair
     * @param $namespace
     * @param $dir
     * @return \Efika\Loader\StandardAutoloader
     */
    public function setNamespace($namespace, $dir)
    {
        $this->namespaces[$namespace] = $this->normalizeDirectory($dir);

        return $this;
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
     * @return \Efika\Loader\StandardAutoloader
     * @throws Exception
     */
    public function setPrefixes($prefixes)
    {
        if (!is_array($prefixes) || $prefixes instanceof \Traversable) {
            require_once 'Exception.php';
            throw new Exception('Invalid Argument. $prefix must be an array or traversable!');
            exit(1);
        }

        foreach ($prefixes as $prefix => $dir) {
            $this->setPrefix($prefix, $dir);
        }

        return $this;
    }

    /**
     * register a prefix/dir pair
     * @param $prefix
     * @param $dir
     * @return \Efika\Loader\StandardAutoloader
     */
    public function setPrefix($prefix, $dir)
    {
        $this->prefixes[$prefix] = $this->normalizeDirectory($dir);

        return $this;
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

    /**
     * Load class by type and
     * @param $className
     * @param $type
     * @return bool|mixed
     */
    protected function loadClass($className, $type)
    {

        foreach ($this->$type as $conversion => $path) {

            if (strpos($className, $conversion) === 0) {
                $trimmedClass = substr($className, strlen($conversion));

                $filename = $this->transformClassNameToFilename($trimmedClass, $path);

                if (stream_resolve_include_path($filename)) {
                    return require_once $filename;
                }

                return false;
            }
        }

    }

    /**
     * Transform class name to a filename
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
