<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \App\Controller;

use \App\Models\UserModel;
use \App\Models\ClientModel;
use \App\Models\AuthModel;
use \App\Models\SuiviModel;
use \App\Models\AssignationModel;


class SuiviController extends Controller { 	
    
    public function modifpointandajoutsuivi(Request $request, Response $response, $args){
      header("Access-Control-Allow-Origin: *");
      $data = $request->getParsedBody();
      $params = json_decode($data['params']);


      $authModel = new AuthModel($this->db);
      $auth = $authModel->isUser($params->token);
      $id_commercial = 0;
      if($auth['message']){
        $id_commercial = $auth['message']['id_user'];
      }
      if(!$auth['message']){
        return $response->withJson(array('errorCode' => false));
      }
      $userModel = new UserModel($this->db);
      $commercial = $userModel->getUser($id_commercial)['message'];

      $id_superviseur = $commercial['depends_on'];
      $superviseur = $userModel->getUser($id_superviseur)['message'];



      $params = $params->data;
      $reponse = $params->reponsesProspect;
      $id_client = $params->id_client;
      $client = $params->client;

      if(is_string($client->activites)){
        $client->activites = json_decode($client->activites);
      }
      if(is_string($client->services)){
        $client->services = json_encode($params->reponsesProspect) ; 
	      $client->fichiers = json_encode($params->piecesFournies) ; 
      }
      $clientModel        = new ClientModel($this->db);
      $updateGerant       = $clientModel->updateGerant($client->id_gerant_point, $client->prenom_gerant, $client->nom_gerant, $client->telephone_gerant, strtolower($client->email_gerant));
      $updateProprietaire = $clientModel->updateProprietaire($client->id_proprietaire_point, $client->prenom_proprietaire, $client->nom_proprietaire, $client->telephone_proprietaire, strtolower($client->email_proprietaire), json_encode($client->adresse_proprietaire));
      $updatePoint        = $clientModel->updatePoint($client->id, json_encode($client->adresse_point), json_encode($client->activites), $client->avis, json_encode($params->reponsesProspect), json_encode($params->piecesFournies) );

      $assignModel                    = new AssignationModel($this->db);
      $id_assigner = $params->id_assignation_origin;
      $deleteAssignationAfterProspect = $assignModel->deleteAssignationAfterProspect($params->id_assignation_origin);

      $infosup = $params->infosup;
      $note = $params->noteprospect;
      $params->datesuivi->dateniveau1 = date("Y-m-d H:i:s");
      $dates_suivi = $params->datesuivi;
      $suiviModel = new SuiviModel($this->db);
      $resp = $suiviModel->ajoutsuivifromprospect(json_encode($dates_suivi), $id_client, $id_commercial, $id_superviseur, json_encode($client), json_encode($commercial), json_encode($superviseur), $note, json_encode($infosup), $id_assigner, json_encode($reponse));

      return $response->withJson($resp);
    }

    public function listsuiviforsuperviseur(Request $request, Response $response, $args){
      header("Access-Control-Allow-Origin: *");
      $data = $request->getParsedBody();
      $params = json_decode($data['params']);


      $authModel = new AuthModel($this->db);
      $auth = $authModel->isUser($params->token);
      $id_superviseur = 0;
      if($auth['message']){
        $id_superviseur = $auth['message']['id_user'];
      }
      if(!$auth['message']){
        return $response->withJson(array('errorCode' => false));
      }

      $suiviModel = new SuiviModel($this->db);
      $datasuivi = $suiviModel->listsuiviforsuperviseur($id_superviseur);
       
      return $response->withJson($datasuivi);
    }

    public function listsuiviarelancerforsuperviseur(Request $request, Response $response, $args){
      header("Access-Control-Allow-Origin: *");
      $data = $request->getParsedBody();
      $params = json_decode($data['params']);


      $authModel = new AuthModel($this->db);
      $auth = $authModel->isUser($params->token);
      $id_superviseur = 0;
      if($auth['message']){
        $id_superviseur = $auth['message']['id_user'];
      }
      if(!$auth['message']){
        return $response->withJson(array('errorCode' => false));
      }

      $suiviModel = new SuiviModel($this->db);
      $datasuiviarelancer = $suiviModel->listsuiviarelancerforsuperviseur($id_superviseur, "A relancer");
       
      return $response->withJson($datasuiviarelancer);
    }

    public function ajoutsuivifromsuperviseur(Request $request, Response $response, $args){
      header("Access-Control-Allow-Origin: *");
      $data = $request->getParsedBody();
      $params = json_decode($data['params']);


      $authModel = new AuthModel($this->db);
      $auth = $authModel->isUser($params->token);
      $id_superviseur = 0;
      if($auth['message']){
        $id_superviseur = $auth['message']['id_user'];
      }
      if(!$auth['message']){
        return $response->withJson(array('errorCode' => false));
      }

      $suiviModel = new SuiviModel($this->db);
      $params = $params->data;

      $usersuperviseur =  $suiviModel->getObjectifAssignerSuperviseur($id_superviseur);
      $ids_assignsuperviseur = json_decode($usersuperviseur['ids_assignation']);
      $ajouobjectifSuperviseur = null;
      if( ($params->qualification == "Valider") and in_array($params->id_assigner.'', $ids_assignsuperviseur)){
        $ajouobjectifSuperviseur =  $suiviModel->ajoutObjectifAssigner($usersuperviseur['id'], $usersuperviseur['atteint']);
      }

      $usercommercial =  $suiviModel->getObjectifAssignerCommercial($params->id_commercial);
      $ids_assigncommercial = json_decode($usercommercial['ids_assignation']);
      $ajouobjectifCommercial = null;
      if( ($params->qualification == "Valider") and in_array($params->id_assigner.'', $ids_assigncommercial)){
        $ajouobjectifCommercial =  $suiviModel->ajoutObjectifAssigner($usercommercial['id'], $usercommercial['atteint']);
      }

      if($params->qualification == "Valider"){
          $suiviModel->en_suivi_assignation($params->client->id, $params->qualification, $params->choixsouscrit);
      }
      if($params->qualification == "Abandonner"){
          $suiviModel->en_suivi_assignation($params->client->id, $params->qualification, "");
          $resp = $suiviModel->deletesuivi($params->id);
      }
      $dates_suivi = $params->dates_suivi;
      $dates_suivi->dateniveau2 = date("Y-m-d H:i:s");
      $resp = null;
      if($params->qualification == "A relancer"){
        $resp = $suiviModel->ajoutsuivifromsuperviseur($params->id, $params->reponse, $params->qualification, json_encode($dates_suivi), 1);      
      }
      else{
        $resp = $suiviModel->ajoutsuivifromsuperviseur($params->id, $params->reponse, $params->qualification, json_encode($dates_suivi), 2);      
      }
      return $response->withJson($resp);
    }

    public function getsuperviseursforperformance(Request $request, Response $response, $args){
        header("Access-Control-Allow-Origin: *");
        $data = $request->getParsedBody();
        $params = json_decode($data['params']);


        $authModel = new AuthModel($this->db);
        $auth = $authModel->isUser($params->token);
        $id_admincom = 0;
        if($auth['message']){
            $id_admincom = $auth['message']['id_user'];
        }
        if(!$auth['message']){
            return $response->withJson(array('errorCode' => false));
        }

        $suiviModel = new SuiviModel($this->db);
        $resp = $suiviModel->getsuperviseursforperformance($id_admincom);

        return $response->withJson(array('errorCode' => true, 'message' => $resp));
    }

    public function getsuperviseursforperformancetest(Request $request, Response $response, $args){
        header("Access-Control-Allow-Origin: *");
        $data = $request->getParsedBody();
        $params = json_decode($data['params']);


        $authModel = new AuthModel($this->db);
        $auth = $authModel->isUser($params->token);
        $id_admincom = 0;
        if($auth['message']){
            $id_admincom = $auth['message']['id_user'];
        }
        if(!$auth['message']){
            return $response->withJson(array('errorCode' => false));
        }

//        $userModel = new UserModel($this->db);
//        $getUsers = $userModel->getSuperviseurs();



        $suiviModel = new SuiviModel($this->db);
        $resp = $suiviModel->getsuperviseursforperformancetest();
        $trier = array( );
        $soustrie = array();
        $soustrie[] = $resp[0];
        for ($i= 1; $i<count($resp); $i++){
            if($resp[$i -1]['id_superviseur'] == $resp[$i]['id_superviseur']){
                $soustrie[] = $resp[$i];
            }
            else{
                $trier[] = $soustrie;
                $soustrie = array();
                $soustrie[] = $resp[$i];
            }
        }
        $trier[] = $soustrie;

        $rassemblertrie = array();
        for ($i= 0; $i<count($trier); $i++){
            $compteuratteint = 0;
            $compteurobjectif = 0;
            for ($j= 0; $j<count($trier[$i]); $j++){
                $compteuratteint += $trier[$i][$j]['atteint'];
                $compteurobjectif += $trier[$i][$j]['objectif'];
            }
            $rassemblertrie[] = array('superviseur' => $trier[$i][0], 'prenom' => $trier[$i][0]['prenom'], 'nom' => $trier[$i][0]['nom'], 'telephone' => $trier[$i][0]['telephone'], 'atteint' => $compteuratteint, 'objectif' => $compteurobjectif);
        }

        return $response->withJson(array('errorCode' => true, 'message' => $rassemblertrie));
    }

    public function getcommerciauxforperformance(Request $request, Response $response, $args){
      header("Access-Control-Allow-Origin: *");
      $data = $request->getParsedBody();
      $params = json_decode($data['params']);


      $authModel = new AuthModel($this->db);
      $auth = $authModel->isUser($params->token);
      $id_admincom = 0;
      if($auth['message']){
        $id_admincom = $auth['message']['id_user'];
      }
      if(!$auth['message']){
        return $response->withJson(array('errorCode' => false));
      }

      $suiviModel = new SuiviModel($this->db);
      $supervisuers = $suiviModel->getsuperviseurs($id_admincom);
      $getcommerciaux = array();
      foreach ($supervisuers as $value) {
        $commerciaux = $suiviModel->getcommerciauxforperformance($value['id_superviseur']);
        foreach ($commerciaux as $value1) {
          $getcommerciaux[] = array_merge($value, $value1);
        }
      }
    
      
      return $response->withJson(array('errorCode' => true, 'message' => $getcommerciaux));
    }

    public function listsuivivalider(Request $request, Response $response, $args){
        header("Access-Control-Allow-Origin: *");
        $data = $request->getParsedBody();
        $params = json_decode($data['params']);

        $authModel = new AuthModel($this->db);
        $auth = $authModel->isUser($params->token);
        if($auth['message']){
            $suiviModel = new SuiviModel($this->db);
            $datasuivivalider = $suiviModel->listsuivivalider();
            return $response->withJson($datasuivivalider);
        }
        if(!$auth['message']){
            return $response->withJson(array('errorCode' => false));
        }
    }

}