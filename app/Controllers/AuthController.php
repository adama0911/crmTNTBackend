<?php

namespace App\Controllers;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \App\Controller;

use \App\Models\AuthModel;


class AuthController extends Controller {

  	public function postLogout(Request $request, Response $response, $args){
  		header("Access-Control-Allow-Origin: *");
    
      $userModel = new AuthModel($this->db);
      $getlogin = $userModel->logout();
      return $response->withJson($getlogin);
    }

    public function postLogin(Request $request, Response $response, $args){
  		header("Access-Control-Allow-Origin: *");
      	
      	$data = $request->getParsedBody();
      	$params = json_decode($data['params']);
      	$userModel = new AuthModel($this->db);
      	$postlogin = $userModel->login($params->login, $params->pwd);
      	return $response->withJson($postlogin);
    }
    
}