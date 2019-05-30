<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    array(
        $config->application->controllersDir,
        $config->application->modelsDir,
        //$config->application->phpexcel,
        $config->application->fpdf
        //$config->application->t_pdf
    )
)->register();
