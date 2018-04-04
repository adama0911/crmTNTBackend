<?php

$app->get('/', App\Controllers\VenteController::class .':accueil');


/*
 $app->group('/vente', function () {

   $this->get('/gettest', App\Controllers\OrangeMoneyController::class .':gettest');

   $this->post('/posttest', App\Controllers\OrangeMoneyController::class .':posttest');


}); 
*/
