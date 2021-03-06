<?php
namespace EfikaTest\Loader;
use Efika\Loader\StandardAutoloader;

require_once '../../functions.php';
require_once getRelatedLibraryClass(__FILE__);



/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-07-29 at 17:01:05.
 */
class StandardAutoloaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var original loaders
     */
    protected $loaders;

    /**
     * @var original include path
     */
    protected $includePath;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // Store original autoloaders
        $this->loaders = spl_autoload_functions();
        if (!is_array($this->loaders)) {
            // spl_autoload_functions does not return empty array when no
            // autoloaders registered...
            $this->loaders = array();
        }

        // Store original include_path
        $this->includePath = get_include_path();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        // Restore original autoloaders
        $loaders = spl_autoload_functions();
        if (is_array($loaders)) {
            foreach ($loaders as $loader) {
                spl_autoload_unregister($loader);
            }
        }

        foreach ($this->loaders as $loader) {
            spl_autoload_register($loader);
        }

        // Restore original include_path
        set_include_path($this->includePath);
    }

    /**
     * @covers Efika\Loader\StandardAutoloader::register
     */
    public function testRegister()
    {
        $loader = new StandardAutoloader();
        $loader->register();
        $loaders = spl_autoload_functions();
        $this->assertTrue(count($this->loaders) < count($loaders));
        $test = array_pop($loaders);
        $this->assertEquals(array($loader, 'autoload'), $test);
    }

    /**
     * @covers Efika\Loader\StandardAutoloader::setNamespace
     * @covers Efika\Loader\StandardAutoloader::autoload
     */
    public function testSetNamespaceAndLoad()
    {
        $loader = new StandardAutoloader();
        $loader->setNamespace('TestLibraryName','/TestLibrary');
        $loader->autoload('TestLibraryName\TestObject');
        $this->assertTrue(class_exists('TestLibraryName\TestObject',false));
    }

    /**
     * @covers Efika\Loader\StandardAutoloader::setPrefix
     * @covers Efika\Loader\StandardAutoloader::autoload
     */
    public function testSetPrefixesAndLoad()
    {
        $loader = new StandardAutoloader();
        $loader->setPrefix('TestLibraryPrefix','/TestLibrary');
        $loader->autoload('TestLibraryPrefix_PrefixedTestObject');
        $this->assertTrue(class_exists('TestLibraryPrefix_PrefixedTestObject',false));
    }
}
