<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

/**
 * An procedural example. This example would also work with complex
 * nested logic for example in MVC structure.
 */

require_once __DIR__ . '/../entryPoint/bootstrap.php';
require_once __DIR__ . '/../utility/OutputHandler.php';

use Efika\EventManager\EventManager;

$outputHandler = new OutputHandler();
$em = new EventManager();

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
    }
)

->attachEventHandler(
    'onDisplayOutput',
    function () use($outputHandler)
    {
        echo PHP_EOL;
    }
)

//assign output
->triggerEvent('onAssignOutput')

//display output
->triggerEvent('onDisplayOutput');
