<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \App\Controller;

use \App\Models\VenteModel;




class VenteController extends Controller {

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

    public function allStockByGroupe(Request $request, Response $response, $args)
    {
        header("Access-Control-Allow-Origin: *");
        
        // static datas

        $idGroupe = 1;

        //registre new facture
        $venteModel = new VenteModel($this->db);

        $groupes = $venteModel->selectAllStockByGroupe($idGroupe);

        return $response->withJson(array("factures" => $groupes));
    }


    public function newFacture(Request $request, Response $response, $args){
        header("Access-Control-Allow-Origin: *");
        
        // static datas
        $idGroupe = 1;
        $typeFacture = 'bon';
        $montant =  5000;
        $date =  "2018-04-02 00:00:00";

        $produits = array();
        
        $produits[] =   array(  "idProduit" => 1 ,
                                "designation" => "paracetamole",
                                "description" => "produit pharmacetique",
                                "prixAchat" => 2000,
                                "prixVente" => 2200,
                                "tva"       => 0,
                                "quantite"  => 2,
                                "datePeremption" => "3018-04-02 00:00:00"
                            );

        $infoSup = json_encode($produits);

        
        $venteModel = new VenteModel($this->db);
    
        //registre new facture
        $id = $venteModel->insertFacture($idGroupe, $typeFacture, $montant , $date, $infoSup);
        //update stock
        for($i=0 ; $i < sizeof($produits); $i++){
            $idProduit = $produits[$i]["idProduit"];
            
            $produit = $venteModel->selectProduit($idGroupe,$idProduit);

            $quantite =  $produit["quantite"] - $produits[$i]["quantite"];

            $statut = $venteModel->updateStock($idProduit,$quantite,$idGroupe);
        }

        return $response->withJson(array("lastInsertId" => $id));
    }

    public function factureHistoryByDay(Request $request, Response $response, $args){
        header("Access-Control-Allow-Origin: *");
        
        // static datas

        $day =  "2018-04-02 00:00:00";


        //registre new facture
        $venteModel = new VenteModel($this->db);

        $factures = $venteModel->selectFactureByDate($date);

        return $response->withJson(array("factures" => $factures ));
    }

    public function factureHistoryByInterval(Request $request, Response $response, $args){
        header("Access-Control-Allow-Origin: *");
        
        // static datas

        $dayInit =  "2018-03-02 00:00:00";
        $dayEnd =  "2018-04-02 00:00:00";

        //registre new facture
        $venteModel = new VenteModel($this->db);

        $factures = $venteModel->selectFactureByInterval($dayInit,$dayEnd);

        return $response->withJson(array("factures" => $factures));
    }

    public function directe(Request $request, Response $response){
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type");
		$data=$request->getParsedBody();
		$params=json_decode($data['params']);
		return $response->WithJson(array('code' =>'ok'));
	}
}


