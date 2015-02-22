<?php
/* @var Composer\Autoload\ClassLoader $autoloader */
$autoloader = include __DIR__ . '/../vendor/autoload.php';
$autoloader->add('Kachit\Collection\Test', __DIR__);
$autoloader->add('Kachit\Collection\Testable', __DIR__);