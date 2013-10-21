<?php
/**
 * @author Marco Bunge
 * @copyright 2012 Marco Bunge <efika@rubymatrix.de>
 */

namespace WebApplication;

use Efika\View\ViewModel;

require_once __DIR__ . '/../../app/boot/bootstrap.php';

$viewModel = new ViewModel();
$viewModel->assignVar('title','My Webapplication');
$viewModel->assignVar('sub_title','Efika');

$viewModel->setViewPath(__DIR__ . '../../app/views/');

echo '<pre>';
var_dump($viewModel->toArray());
echo '</pre>';
