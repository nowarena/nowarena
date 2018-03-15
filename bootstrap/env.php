<?php

$app->detectEnvironment(function () use ($app) {

    $path = $app['path.base'] . '/env/';

    if (!isset($_SERVER['HTTP_HOST'])) {
        exit("HTTP_HOST not set and can load .env in " . __FILE__);
        $dotenv = new Dotenv\Dotenv($path, '.env');
        $dotenv->load($app['path.base'], $app->environmentFile());
    }

    $pos = mb_strpos($_SERVER['HTTP_HOST'], '.');
    $prefix = '';
    if ($pos) {
        $prefix = mb_substr($_SERVER['HTTP_HOST'], 0, $pos);
    }
    $filename = '.env.' . $prefix;
    $pathAndFilename = $path . $filename;
    if (!file_exists($pathAndFilename)) {
        $filename = '.env';
        $pathAndFilename = $path . $filename;
        exit($pathAndFilename);
    }

    $dotenv = new Dotenv\Dotenv($path, $filename);
    $dotenv->load($pathAndFilename);

});
