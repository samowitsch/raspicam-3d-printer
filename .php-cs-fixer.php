<?php
/*
 * This document has been generated with
 * https://mlocati.github.io/php-cs-fixer-configurator/#version:3.4.0|configurator
 * you can change this configuration by importing this file.
 */
$config = new PhpCsFixer\Config();
return $config
    ->setRules([
        '@PSR12' => true,
        '@PSR2' => true,
    ])
    ->setFinder(PhpCsFixer\Finder::create()
        //->in(__DIR__)
        ->exclude([
	    'compilation_cache',
	    '.git',
	    'vendor',
        ])
        ->append([
		'cam.php',
		'timelap.php',
		'cam-archive.php',
		'timelap-archive.php',
        ])
    )
;
