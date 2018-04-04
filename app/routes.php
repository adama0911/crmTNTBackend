<?php

$app->get('/', App\Controllers\VenteController::class .':accueil');

$app->group('/client',function(){

    $this->post('/nouveauclient',App\Controllers\HomeController::class .':nouveauclient');
    
    $this->post('/bloquerclient',App\Controllers\HomeController::class .':bloquerclient');
    
    $this->post('/modifierclient',App\Controllers\HomeController::class .':modifierclient');

    
});
$app->group('/vente',function(){

    $this->post('/directe',App\Controllers\VenteController::class .':directe');
    
    
});



/*
 $app->group('/vente', function () {

   $this->get('/gettest', App\Controllers\OrangeMoneyController::class .':gettest');

   $this->post('/posttest', App\Controllers\OrangeMoneyController::class .':posttest');


}); 
*/
