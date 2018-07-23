<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \App\Controller;

use \App\Models\AssignationModel;
use \App\Models\AuthModel;


class AssignationController extends Controller {

  	public function assignationsuperviseur(Request $request, Response $response, $args){
      header("Access-Control-Allow-Origin: *");
      $data = $request->getParsedBody();
      $params = json_decode($data['params']);

      $authModel = new AuthModel($this->db);
      $auth = $authModel->isUser($params->token);
      if($auth['message']){
        $assignModel = new AssignationModel($this->db);
        $resp = null;
        $params = $params->data;
        $ids_assigner = [];
        foreach ($params->assignes as $value) {
          $infosup = $params->infosup;
          $infosup->date_assignationsuperviser = date("Y-m-d H:i:s");
          $infosup->commentaireforsuperviseur = $value->commentaire;
          $resp = $assignModel->assignationsuperviseur(
            $value->id, json_encode($value), $params->superviseur->id, json_encode($params->superviseur),
            $value->commentaire, $params->objectifsuperviseur, $params->region, $params->zone, $params->souszone, json_encode($infosup)
          );
          $ids_assigner[] = $resp['message'];
          $assignModel->en_assignation($value->id);
        }
        $resp = $assignModel->objectifassignation($params->region, $params->zone, $params->souszone, $params->superviseur->id, json_encode($ids_assigner), $params->objectifsuperviseur, "superviseur");
        return $response->withJson(array('errorCode' => true, 'message' => $resp));
      }
      else{
        return $response->withJson(array('errorCode' => false));
      }
    }

    public function assignationcommercial(Request $request, Response $response, $args){
      header("Access-Control-Allow-Origin: *");
      $data = $request->getParsedBody();
      $params = json_decode($data['params']);

      $assignModel = new AssignationModel($this->db);
      $authModel = new AuthModel($this->db);
      $auth = $authModel->isUser($params->token);
      if($auth['message']){
        $resp = null;
        $params = $params->data;
        $ids_assigner = [];
        $azone = null;
        $asouszone = null;
        $aobjectif = null;
        foreach ($params->assignes as $value) {
          $infosup = $params->infosup;
          $infosup->date_assignationsuperviser = $value->infosup->date_assignationsuperviser;
          $infosup->commentaireforsuperviseur = $value->infosup->commentaireforsuperviseur;
          $infosup->objectifsuperviseur = $value->infosup->objectifsuperviseur;
          $infosup->date_assignationcommercial = date("Y-m-d H:i:s");
          $infosup->commentaireforcommercial = $value->commentaire;
          $resp = $assignModel->assignationcommercial(
            $value->id,
            $params->commercial->id,
            json_encode($params->commercial),
            json_encode($infosup),
            $value->commentaire
          );
          $ids_assigner[] = $value->id;
          $aregion = $value->region;
          $azone = $value->zone;
          $asouszone = $value->sous_zone;
          $aobjectif = $params->infosup->objectifcommercial;
        }
        $resp = $assignModel->objectifassignation($aregion, $azone, $asouszone, $params->commercial->id, json_encode($ids_assigner), $aobjectif, "commercial");
        return $response->withJson(array('errorCode' => true, 'message' => $resp));
      }
      else{
        return $response->withJson(array('errorCode' => false));
      }
    }

    public function getAssignationsBySuperviseur(Request $request, Response $response, $args){
      header("Access-Control-Allow-Origin: *");
      $data = $request->getParsedBody();
      $params = json_decode($data['params']);

      $userModel = new AssignationModel($this->db);

      $id_superviseur = null;
      $authModel = new AuthModel($this->db);
      $auth = $authModel->isUser($params->token);
      if($auth['message']){
        $id_superviseur = $auth['message']['id_user'];
      }
      if(!$auth['message']){
        return $response->withJson(array('errorCode' => false));
      }

      
      $getUsers = $userModel->getAssignationsBySuperviseur($id_superviseur);
      return $response->withJson($getUsers);
    }

    public function getAssignationsByCommercial(Request $request, Response $response, $args){
        header("Access-Control-Allow-Origin: *");
        $data = $request->getParsedBody();
        $params = json_decode($data['params']);

        $id_commercial = 0;
        $authModel = new AuthModel($this->db);
        $auth = $authModel->isUser($params->token);
        if($auth['message']){
            $id_commercial = $auth['message']['id_user'];
        }
        if(!$auth['message']){
            return $response->withJson(array('errorCode' => false));
        }
        $userModel = new AssignationModel($this->db);
        $getUsers = $userModel->getAssignationsByCommercial($id_commercial);
        return $response->withJson(array('errorCode' => true, 'message' => $getUsers));
    }

}