<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */
namespace WebApplication;

use Efika\Config\Config;

require_once __DIR__ . '/../../app/boot/bootstrap.php';

$config = [
    'application' => [
        'mark' => 'one'
    ]
];

$config = new Config($config);

var_dump($config);