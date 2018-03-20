<?php

$app->detectEnvironment(function () use ($app) {

    $path = $app['path.base'] . '/env/';

    if (!isset($_SERVER['HTTP_HOST'])) {
        // HTTP_HOST=abbotkinneybl.nowarena.com php artisan migrate:migrate
        exit("HTTP_HOST not set and can not load .env in " . __FILE__);
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
        exit("\n\npathAndFile does not exist:" . $pathAndFilename . "\n\n");
    }

    $dotenv = new Dotenv\Dotenv($path, $filename);
    $dotenv->load($pathAndFilename);

});
