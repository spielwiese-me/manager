<?php

use Spielwiese\Manager\Container\ContainerService;
use Spielwiese\Manager\LoginService;
use Spielwiese\Manager\Route;
use Spielwiese\Manager\Routing\Request;

Route::post('/api/login', function (Request $request) {
    $password = $request->get('password');
    if ($password === null) {
        return Route::response('Bad Request', 400);
    }

    $success = LoginService::logIn($password);
    if ($success === null) {
        return Route::response('Server Error', 500);
    }

    return Route::json([
        'success' => $success,
    ]);
});

Route::post('/api/logout', function () {
    LoginService::logOut();
});

Route::post('/api/container/update', function (Request $request) {
    if (!LoginService::isLoggedIn()) {
        return Route::response('Unauthorized', 401);
    }

    $container = $request->get('container');
    if ($container === null) {
        return Route::response('Bad Request', 400);
    }

    ContainerService::updateContainer($container);

    return Route::json([]);
});

Route::post('/api/container/restart', function (Request $request) {
    if (!LoginService::isLoggedIn()) {
        return Route::response('Unauthorized', 401);
    }

    $container = $request->get('container');
    if ($container === null) {
        return Route::response('Bad Request', 400);
    }

    ContainerService::restartContainer($container);

    return Route::json([]);
});
