<?php

namespace App\Models;


class CaisseModel {
	private $_db = null;

	public function __construct($db){
		$this->_db = $db;
	}

    public function getClientUsers(){
        $users = array();
        $q = $this->_db->query('SELECT cu.* FROM clientUsers cu');
        while( $user = $q->fetch() ){
            $users[] = $user;
        }
        $q->closeCursor();
        return $users;
    }

    public function getAllClients(){
        $clients = array();
        $q = $this->_db->query('SELECT c.* FROM Clients c');
        while( $client = $q->fetch() ){
            $clients[] = $client;
        }
        $q->closeCursor();
        return $clients;
    }

    public function getAllEmployes(){
        $employes = array();
        $q = $this->_db->query('SELECT e.* FROM Employes e');
        while( $employe = $q->fetch() ){
            $employes[] = $employe;
        }
        $q->closeCursor();
        return $employes;
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

    public function getAllFournisseurs(){
        $fournisseurs = array();
        $q = $this->_db->query('SELECT f.* FROM Fournisseurs f');
        while( $fournisseur = $q->fetch() ){
            $fournisseurs[] = $fournisseur;
        }
        $q->closeCursor();
        return $fournisseurs;
    }

    public function getAllGroupes(){
        $groupes = array();
        $q = $this->_db->query('SELECT g.* FROM Groupes g');
        while( $groupe = $q->fetch() ){
            $groupes[] = $groupe;
        }
        $q->closeCursor();
        return $groupes;
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

}