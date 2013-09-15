<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace Efika\Common;

/**
 * This trait allows you to implement the singleton pattern
 *
 * Example Usage:
 * <p>
 *     <?php
 *          class SingletonObject {
 *              use Efika\Common\SingletonTrait;
 *
 *              public function foo(){
 *                  return 'bar';
 *              }
 *          }
 *
 *          //Execute method foo()
 *          SingletonObject::getInstance()->foo();
 *     ?>
 * </p>
 */
trait SingletonTrait
{
    /**
     * Contains object instance
     * @var null
     */
    private static $instance = null;

    /**
     * Returns a instance of an object
     * @static
     * @return null
     */
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new static;
        }
        return self::$instance;
    }

    private function __clone()
    {
    }

    private function __construct()
    {
    }
}