<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

return [
    'appNs' => 'WebApplication',
    'autoloader' => [
        'Efika\\' => dirname(__FILE__) . '/../../core/src/rubymatrix/Efika/',
        'WebApplication\\' => dirname(__FILE__) . '/../src/Classes/',
    ],
    'events' => [
        'application.init' => function ($e) {
            //do something
        }
    ],
    'modules' => [],
    'router' => [
        '/cmd/(?P<command>\w+)' => [
            'route' => [
                'params' => 'user/foo/group/bar'
            ],
            'dispatchMode' => 'cmd',
        ],
        '/cmd/(?P<command>\w+)/(?P<params>[a-zA-Z0-9_/\-]+)' => [
            'route' => ':command/:params',
            'dispatchMode' => 'cmd',
        ],
        '/(?P<controller>\w+)/(?P<actionId>\w+)(/(?P<params>[a-zA-Z0-9_/]+)?)?' => [
            'route' => [
                'route' => ':controller/:actionId/:params',
            ],
            'dispatchMode' => 'mvc',
        ],
        '(/)?' => [
            'controller' => 'index',
            'actionId' => 'index',
            'dispatchMode' => 'mvc',
        ],
    ],
];