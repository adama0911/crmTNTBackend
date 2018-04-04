<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \App\Controller;

use \App\Models\CaisseModel;


class CaisseController extends Controller {

    public function accueil(Request $request, Response $response, $args){
          header("Access-Control-Allow-Origin: *");
          
        $utilModel = new CaisseModel($this->db);

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
}