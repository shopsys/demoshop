<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;

$symfonyDumpFunctionPath = 'vendor/symfony/var-dumper/Resources/functions/dump.php';

if (file_exists(__DIR__ . '/../' . $symfonyDumpFunctionPath)) {
    require_once __DIR__ . '/../' . $symfonyDumpFunctionPath;
}

if (file_exists(__DIR__ . '/../../' . $symfonyDumpFunctionPath)) {
    require_once __DIR__ . '/../../' . $symfonyDumpFunctionPath;
}

$loader = file_exists(__DIR__ . '/../vendor/autoload.php') ? require __DIR__ . '/../vendor/autoload.php' : require __DIR__ . '/../../vendor/autoload.php';
/* @var $loader \Composer\Autoload\ClassLoader */

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

return $loader;
