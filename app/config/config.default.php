<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

return [
    'appNs' => 'WebApplication',
    'autoloader' => [
        'Efika\\' => dirname(__FILE__) . '/../../library/Efika/',
    ],
    'router' => [
        '(/)?' => [
            'controller' => 'index',
            'actionId' => 'index',
            'dispatchMode' => 'mvc',
        ],
    ],
    'modules' => [],
    'events' => [],
];