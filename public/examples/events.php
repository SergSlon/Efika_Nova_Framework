<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

use Efika\Common\Logger;
use Efika\EventManager\EventManager;
use WebApplication\OutputHandler;


/**
 * An procedural example. This example would also work with complex
 * nested logic for example in MVC structure.
 */

require_once __DIR__ . '/../../app/boot/bootstrap.php';

Logger::getInstance()->addMessage('init start');

$outputHandler = new OutputHandler();
$em = new EventManager();

Logger::getInstance()->addMessage('init complete');
Logger::getInstance()->addMessage('attaching start');

//a simple chain
//add some lines of text
$em->attachEventHandler(
    'onAssignOutput',
    function () use($outputHandler)
    {
        $outputHandler->addContent('Hello World');
        $outputHandler->addContent('some lines of code');
    }
)

//execute some logic
->attachEventHandler(
    'onAssignOutput',
    function () use($outputHandler)
    {
        $outputHandler->addContent('<b>A List of numbers</b>');
        $outputHandler->addContent('<ul>');
        for ($n = 0; $n < 10; $n++) {
            $outputHandler->addContent('<li>' . $n . '</li>');
        }
        $outputHandler->addContent('</ul>');
    }
)

//set html mode to XHTML
->attachEventHandler(
    'onDisplayOutput',
    function () use($outputHandler)
    {
        $outputHandler->setHtmlMode(ENT_XHTML);
    }
)

//set encoding to ISO-8859-1
->attachEventHandler(
    'onBeforeDisplayOutput',
    function () use($outputHandler)
    {
        $outputHandler->setEncoding('ISO-8859-1');
    }
)

//sanitize html
->attachEventHandler(
    'onBeforeDisplayOutput',
    function () use($outputHandler)
    {
        foreach ($outputHandler->content as $line => $content) {
            $outputHandler->content[$line] = htmlentities(
                $content,
                $outputHandler->getHtmlMode(),
                $outputHandler->getEncoding()
            );
        }
    }
)

//display output
//execute an event -> nested event execution
->attachEventHandler(
    'onDisplayOutput',
    function () use($outputHandler,$em)
    {
        //execute onBeforeDisplayOutput event
        $em->triggerEvent('onBeforeDisplayOutput');
        $outputHandler->display('<br />');
    },
        100
)

->attachEventHandler(
    'onDisplayOutput',
    function () use($outputHandler)
    {
        echo 'Attach after but execute before';
    },
        5000
);

Logger::getInstance()->addMessage('attaching complete');
Logger::getInstance()->addMessage('triggering start');

//assign output
$em->triggerEvent('onAssignOutput');

//display output
$em->triggerEvent('onDisplayOutput');

$em->triggerEvent('onUnknown');

Logger::getInstance()->addMessage('triggering complete');

echo "<pre>";
echo Logger::getInstance()->toText();
echo "</pre>";
