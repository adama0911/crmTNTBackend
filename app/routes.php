<?php

$app->get('/', App\Controllers\HomeController::class .':accueil');


/*
 $app->group('/exemple', function () {

   $this->get('/gettest', App\Controllers\OrangeMoneyController::class .':gettest');

   $this->post('/posttest', App\Controllers\OrangeMoneyController::class .':posttest');


}); 
*/
