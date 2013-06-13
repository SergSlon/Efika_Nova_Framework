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
//            var_dump('init event from production config');
//            echo '<pre>';
//            print_r($e->getArguments());
        }
    ]
];