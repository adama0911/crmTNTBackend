<?php

$app->post('/', App\Controllers\HomeController::class .':accueil');

/*$app->group('/auth', function () {

	$this->post('/ok', App\Controllers\HomeController::class .':accueil');

});*/

$app->group('/authenticat', function () {

	$this->post('/login', App\Controllers\AuthController::class .':postLogin');

	$this->post('/logout', App\Controllers\AuthController::class .':postLogout');

});





