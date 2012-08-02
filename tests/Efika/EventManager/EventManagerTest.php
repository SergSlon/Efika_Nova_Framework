<?php
namespace EfikaTest\EventManager;


use Efika\EventManager\EventManager;
use ReflectionClass;
use EfikaTest\EventManager\TestLibrary\CustomEvent;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-07-29 at 15:42:32.
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {

    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testUsesEventManagerTrait()
    {
        $em = new EventManager();
        $this->assertTrue(trait_exists('Efika\EventManager\EventManagerTrait', false));
        $reflection = new ReflectionClass($em);
        $this->assertTrue(in_array('Efika\EventManager\EventManagerTrait', $reflection->getTraitNames()));
        $this->assertTrue($reflection->getMethods() > 0);

    }

    /**
     * @covers Efika\EventManager\EventManager::attachEventHandler
     */
    public function testAttachEventHandler()
    {
        $em = new EventManager;
        $em->attachEventHandler('onTest', function($e)
        {
        });

        $this->assertNotEmpty($em->getEventHandler('onTest'), 'Empty Callback');
        $this->assertArrayHasKey('onTest', $em->getEventHandlers(), 'Handler does not exist');
        $this->assertTrue($em->hasEventHandler('onTest'), 'Event has no handler');

    }

    /**
     * @covers Efika\EventManager\EventManager::attachEventHandler
     * @covers Efika\EventManager\EventManager::triggerEvent
     */
    public function testTriggerEvent()
    {
        $em = new EventManager;
        $em->attachEventHandler('onTest', function($e)
        {
            $this->assertInstanceOf('Event', $e);
        });

        $this->assertInstanceOf(
            'Efika\EventManager\EventResponseInterface',
            $em->triggerEvent('onTest')
        );
        $this->assertInstanceOf(
            'Efika\EventManager\EventResponseInterface',
            $em->triggerEvent((new CustomEvent)->setName('onTest'))
        );


    }

    public function testInjectEventResponseClass()
    {
        //@todo injection tests
    }


    public function testInjectEventClass()
    {
        //@todo injection tests
    }
}
