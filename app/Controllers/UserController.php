<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \App\Controller;

use \App\Models\UserModel;
use \App\Models\AuthModel;


class UserController extends Controller {

  	public function getAdministratifs(Request $request, Response $response, $args){
  		header("Access-Control-Allow-Origin: *");
      
      	$userModel = new UserModel($this->db);
      	$getUsers = $userModel->getAdministratifs();
      	return $response->withJson($getUsers);
    }

    public function getCommerciaux(Request $request, Response $response, $args){
  		header("Access-Control-Allow-Origin: *");
      
      	$userModel = new UserModel($this->db);
      	$getUsers = $userModel->getCommerciaux();
      	return $response->withJson($getUsers);
    }

    public function getSuperviseurs(Request $request, Response $response, $args){
  		header("Access-Control-Allow-Origin: *");
      
      	$userModel = new UserModel($this->db);
      	$getUsers = $userModel->getSuperviseurs();
      	return $response->withJson($getUsers);
    }

    public function getAdminCommerciaux(Request $request, Response $response, $args){
  		header("Access-Control-Allow-Origin: *");
      
      	$userModel = new UserModel($this->db);
      	$getUsers = $userModel->getAdminCommerciaux();
      	return $response->withJson($getUsers);
    }

    public function getCommerciauxBySuperviseur(Request $request, Response $response, $args){
        header("Access-Control-Allow-Origin: *");
        $data = $request->getParsedBody();
        $params = json_decode($data['params']);

        $userModel = new UserModel($this->db);

        $authModel = new AuthModel($this->db);
        $auth = $authModel->isUser($params->token);
        if($auth['message']){
            $getUsers = $userModel->getCommerciauxBySuperviseur($auth['message']['id_user']);
            return $response->withJson(array('errorCode' => true, 'message' => $getUsers));
        }
        else{
            return $response->withJson(array('errorCode' => false));
        }
    }

    public function ajoutCommercialBySuperviseur(Request $request, Response $response, $args){
        header("Access-Control-Allow-Origin: *");
        $data = $request->getParsedBody();
        $params = json_decode($data['params']);

        $userModel = new UserModel($this->db);

        $authModel = new AuthModel($this->db);
        $auth = $authModel->isUser($params->token);
        if($auth['message']){
            $params = $params->data;
            $getUsers = $userModel->ajoutCommercialBySuperviseur($params->prenom, $params->nom, strtolower($params->email), $params->pwd, $auth['message']['id_user'], $params->telephone);
            return $response->withJson(array('errorCode' => true, 'message' => $getUsers));
        }
        else{
            return $response->withJson(array('errorCode' => false));
        }
    }

    public function ajoutSuperviseur(Request $request, Response $response, $args){
        header("Access-Control-Allow-Origin: *");
        $data = $request->getParsedBody();
        $params = json_decode($data['params']);

        $userModel = new UserModel($this->db);

        $authModel = new AuthModel($this->db);
        $auth = $authModel->isUser($params->token);
        if($auth['message']){
            $params = $params->data;
            $getUsers = $userModel->ajoutSuperviseur($params->prenom, $params->nom, strtolower($params->email), $params->pwd, $auth['message']['id_user'], $params->telephone);
            return $response->withJson(array('errorCode' => true, 'message' => $getUsers));
        }
        else{
            return $response->withJson(array('errorCode' => false));
        }
    }

    public function getAdminAdministratifs(Request $request, Response $response, $args){
  		header("Access-Control-Allow-Origin: *");
      
      	$userModel = new UserModel($this->db);
      	$getUsers = $userModel->getAdminAdministratifs();
      	return $response->withJson($getUsers);
    }
    
    public function getPdvs(Request $request, Response $response, $args){
  		header("Access-Control-Allow-Origin: *");
      
      	$userModel = new UserModel($this->db);
      	$getUsers = $userModel->getPdvs();
      	return $response->withJson($getUsers);
    }
    
    public function getAdminPdvs(Request $request, Response $response, $args){
  		header("Access-Control-Allow-Origin: *");
      
      	$userModel = new UserModel($this->db);
      	$getUsers = $userModel->getAdminPdvs();
      	return $response->withJson($getUsers);
    }
    
}