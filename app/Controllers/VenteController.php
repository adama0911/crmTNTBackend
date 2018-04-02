<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \App\Controller;

use \App\Models\UserModel;


class VenteController extends Controller {

    public function directe(Request $request, Response $response){
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Headers: Content-Type");
		$data=$request->getParsedBody();
		$params=json_decode($data['params']);
		return $response->WithJson(array('code' =>'ok'));
	}
}

