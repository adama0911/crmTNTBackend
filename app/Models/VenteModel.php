<?php

namespace App\Models;


class VenteModel {
	private $_db = null;

	public function __construct($db){
		$this->_db = $db;
	}



    public function getAllFactures(){
        $factures = array();
        $q = $this->_db->query('SELECT f.* FROM Factures f');
        while( $facture = $q->fetch() ){
            $factures[] = $facture;
        }
        $q->closeCursor();
        return $factures;
    }


    public function getAllProduits(){
        $produits = array();
        $q = $this->_db->query('SELECT p.* FROM Produits p');
        while( $produit = $q->fetch() ){
            $produits[] = $produit;
        }
        $q->closeCursor();
        return $produits;
    }

    public function getAllStock(){
        $stocks = array();
        $q = $this->_db->query('SELECT s.* FROM Stock s');
        while( $stock = $q->fetch() ){
            $stocks[] = $stock;
        }
        $q->closeCursor();
        return $stocks;
    }

	public function insertFacture($idGroupe, $typeFacture, $montant , $date, $infoSup){

		$sql = 'INSERT INTO Factures(idGroupe, typeFacture, montant, date, infoSup)
				VALUES(:idGroupe,:typeFacture, :montant, :date,:infoSup)';

		$q = $this->_db->prepare($sql);

		$q->execute(array(  ':idGroupe' => $idGroupe,
			 			    ':typeFacture' => $typeFacture,
							':montant' => $montant,
							':date' => $date,
							':infoSup' => $infoSup
						));

		return $this->_db->lastInsertId();
	}

	public function selectFactureByDate($date){
		$factures = array();

		$sql = 'SELECT f.* 
                FROM Factures f
				WHERE f.date =:date';

		$q = $this->_db->prepare($sql);

		$q->execute(array(':date' => $date));

		while( $facture = $q->fetch() ){
			$factures[] = $facture;
		}

		$q->closeCursor();
        
        return $factures;
    }
    
    public function selectFactureByInterval($dayInit,$dayEnd){
        $factures = array();

		$sql = 'SELECT f.* 
                FROM Factures f
				WHERE f.date >=:dayInit AND f.date <=:dayEnd';

		$q = $this->_db->prepare($sql);

		$q->execute(array(':dayInit' => $dayInit,':dayEnd' => $dayEnd ));

		while( $facture = $q->fetch() ){
			$factures[] = $facture;
		}

		$q->closeCursor();
        
        return $factures; 
    }

    public function selectAllStockByGroupe($idGroupe){
        $stocks = array();

        $sql = 'SELECT s.* 
                FROM Stock s
                WHERE idGroupe=:idGroupe';
        
		$q = $this->_db->prepare($sql);

        $q->execute(array(':idGroupe' => $idGroupe));
        

        while( $stock = $q->fetch() ){
            $stocks[] = $stock;
        }

        $q->closeCursor();
        return $stocks;
    }

    public function selectProduit($idGroupe,$idProduit){

        $sql = 'SELECT s.* 
                FROM Stock s
                WHERE idGroupe=:idGroupe AND idProduit=:idProduit';
        
		$q = $this->_db->prepare($sql);

        $q->execute(array(':idGroupe' => $idGroupe, ':idProduit' => $idProduit));
        $re = $q->fetch();

        $q->closeCursor();

        return  $re;
    }

    public function updateStock($idProduit,$quantite,$idGroupe){
        $statut = "true";

        $sql = 'UPDATE Stock 
                SET quantite=:quantite 
                WHERE idProduit=:idProduit AND idGroupe=:idGroupe';

        $q = $this->_db->prepare($sql);

        try{

            $q->execute(array(  ':idProduit' => $idProduit,
                                ':quantite' => $quantite,
                                ':idGroupe' => $idGroupe
                            ));

            $q->closeCursor();
            
        }catch(exception $e) {
            $statut =  "exeption: ".$e; 
        }


        return $statut;
    }

}
