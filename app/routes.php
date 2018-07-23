<?php

$app->post('/', App\Controllers\HomeController::class .':accueil');

/*$app->group('/auth', function () {

	$this->post('/ok', App\Controllers\HomeController::class .':accueil');

});*/

$app->group('/authenticat', function () {

	$this->post('/login', App\Controllers\AuthController::class .':postLogin');

	$this->post('/logout', App\Controllers\AuthController::class .':postLogout');

});


$app->group('/user', function () {

	$this->get('/adminadministratif', App\Controllers\UserController::class .':getAdminAdministratifs');

	$this->get('/admincommmercial', App\Controllers\UserController::class .':getAdminCommerciaux');

	$this->get('/administratif', App\Controllers\UserController::class .':getAdministratifs');

	$this->get('/commercial', App\Controllers\UserController::class .':getCommerciaux');

	$this->post('/commercial', App\Controllers\UserController::class .':getCommerciauxBySuperviseur');

    $this->get('/superviseur', App\Controllers\UserController::class .':getSuperviseurs');

    $this->post('/ajoutcommercial', App\Controllers\UserController::class .':ajoutCommercialBySuperviseur');

    $this->post('/ajoutsuperviseur', App\Controllers\UserController::class .':ajoutSuperviseur');

});




