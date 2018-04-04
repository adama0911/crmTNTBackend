<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \App\Controller;

use \App\Models\UtilModel;


class HomeController extends Controller {

    public function accueil(Request $request, Response $response, $args){
          header("Access-Control-Allow-Origin: *");
          
        $utilModel = new UtilModel($this->db);

        $clientUsers     =  $utilModel->getClientUsers();
        $clients         =  $utilModel->getAllClients();
        $employes        =  $utilModel->getAllEmployes();
        $factures        =  $utilModel->getAllFactures();
        $fournisseurs    =  $utilModel->getAllFournisseurs();
        $groupes         =  $utilModel->getAllGroupes();
        $produits        =  $utilModel->getAllProduits();
        $stocks           =  $utilModel->getAllStock();

        return $response->withJson(array("clientUsers" => $clientUsers, 
                                        "clients" => $clients,
                                        "employes" => $employes,
                                        "factures" => $factures,
                                        "fournisseurs" => $fournisseurs,
                                        "groupes" => $groupes,
                                        "produits" => $produits,
                                        "stocks" => $stocks));
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

