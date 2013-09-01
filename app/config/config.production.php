<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

return [
    'autoloader' => [
        'WebApplication\\' => dirname(__FILE__) . '/../src/Classes/',
    ],
    'events' => [
        'application.init' => function ($e) {
            //do something
        }
    ],
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
        ]
    ],
];