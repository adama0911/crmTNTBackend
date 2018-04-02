<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \App\Controller;

use \App\Models\UserModel;


class HomeController extends Controller {

    public function accueil(Request $request, Response $response, $args){
  	header("Access-Control-Allow-Origin: *");
        //$this->_logger->addInfo("Fist Use");
        return $response->withJson(array("message", "WELCOME"));
    }

    public function nouveauclient(Request $request, Response $response){
		header("Access-Control-Allow-Origin: *");
		return $response->WithJson(array('code' =>'ok'));
	}
	
    public function bloquerclient(Request $request, Response $response){
		header("Access-Control-Allow-Origin: *");
		return $response->WithJson(array('code' =>'client bloquer'));
	}
	
    public function modifierclient(Request $request, Response $response){
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type");
		return $response->WithJson(array('code' =>'client modifier'));
	}
}
