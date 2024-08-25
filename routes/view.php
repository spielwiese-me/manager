<?php

use Spielwiese\Manager\LoginService;
use Spielwiese\Manager\Route;
use Spielwiese\Manager\Container\ContainerService;

Route::get('/', function () {
    return Route::render('main.twig', [
        'loggedIn' => LoginService::isLoggedIn(),
        'managed' => ContainerService::getManaged(),
    ]);
});
