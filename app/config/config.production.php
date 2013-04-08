<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

return [
    'application' => [
        'events' => [
            'application.init' => function($e){
                var_dump('init event from production config');
            }
        ]
    ]
];