<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

require_once __DIR__ . '/../entryPoint/bootstrap.php';

echo '<pre>';

$em = new \Efika\EventManager\EventManager();

/**
 * 1. Attach an aggregate
 */

class CustomEventAggregate implements \Efika\EventManager\EventHandlerAggregateInterface {

    /**
     * Attach events to parent event observer
     *
     * @param \Efika\EventManager\EventManagerInterface $parent
     *
     * @return mixed
     */
    public function attach($parent)
    {
        $parent->attachEventHandler(
            'custom.eventName.foo',
            function($e){
                echo 'Attach event(\'s)';
            }
        );

        $parent->attachEventHandler(
            array(
                'custom.eventName.foo' => function($e){
                    echo 'Attach event(\'s) in array';
                },

                'custom.eventName.bar' => array (
                    function($e){
                        echo 'attach callbacks';
                    },

                    function($e){
                        echo 'attach a second callback';
                    }
                )
            )
        );
    }

    /**
     * Detach events from parent event observer
     *
     * @param $parent
     *
     * @return mixed
     */
    public function detach($parent)
    {
        // TODO: Implement detach() method.
    }
}

function anEventCallbackAsFunction($e){
    echo __FUNCTION__ . ': Some logic';
}

class CustomObject {
    function anEventCallbackAsMethod($e){
        echo __FUNCTION__ . ': Some logic';
    }
}

echo 'attach aggregate';
$em->attachEventHandler(new CustomEventAggregate());
$em->attachEventHandler('another.events', array('anEventCallbackAsFunction'));
$em->attachEventHandler('another.events', array(new CustomObject(), 'anEventCallbackAsMethod'));

print_r($em->getEventHandlers());

echo '<b>trigger custom.eventName.foo</b>';
$em->triggerEvent('custom.eventName.foo',
    function($responses){
        print_r($responses);
    }
);

echo '<b>trigger custom.eventName.bar</b>';
$em->triggerEvent('custom.eventName.bar',
    function($responses){
        print_r($responses);
    }
);

echo '<b>trigger another.events</b>';
$em->triggerEvent('another.events',
    function($responses){
        print_r($responses);
        return true;
    }
);

echo '</pre>';