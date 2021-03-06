<?php
namespace EfikaTest\EventManager;


use Efika\EventManager\EventManager;
use ReflectionClass;
use EfikaTest\EventManager\TestLibrary\CustomEvent;
use EfikaTest\EventManager\TestLibrary\CustomEventResponse;

/**
 * Generated by PHPUnit_SkeletonGenerator.
 */
class EventManagerTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    public function testUsesEventManagerTrait()
    {
        $em = new EventManager();
        $this->assertTrue(trait_exists('Efika\EventManager\EventManagerTrait', false));
        $reflection = new ReflectionClass($em);
        $this->assertContains('Efika\EventManager\EventManagerTrait', $reflection->getTraitNames());
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
    /**
     * @covers Efika\EventManager\EventManager::setEventResponseObject
     * @covers Efika\EventManager\EventManager::getEventResponseObject
     */
    public function testInjectEventResponseObject()
    {
        $em = new EventManager;
        $em->setEventResponseObject(new CustomEventResponse());
        $this->assertInstanceOf(
            'EfikaTest\EventManager\TestLibrary\CustomEventResponse',
            $em->getEventResponseObject()
        );
    }

    /**
     * @covers Efika\EventManager\EventManager::setEventObject
     * @covers Efika\EventManager\EventManager::getEventObject
     */
    public function testInjectEventObject()
    {
        $em = new EventManager;
        $em->setEventObject(new CustomEvent());
        $this->assertInstanceOf(
            'EfikaTest\EventManager\TestLibrary\CustomEvent',
            $em->getEventObject()
        );
    }
    /**
     * @covers Efika\EventManager\EventManager::getEventResponseObject
     */
    public function testGetEventResponseObject()
    {
        $em = new EventManager;
        $this->assertInstanceOf(
            'Efika\EventManager\EventResponseInterface',
            $em->getEventResponseObject()
        );
    }

    /**
     * @covers Efika\EventManager\EventManager::getEventObject
     */
    public function testGetEventObject()
    {
        $em = new EventManager;
        $this->assertInstanceOf(
            'Efika\EventManager\EventInterface',
            $em->getEventObject()
        );
    }
}
