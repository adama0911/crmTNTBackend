<?php

namespace App;


use Slim\Container;


class Controller {

  protected $db;
  protected $_logger;

  public function __construct(Container $c) {
      $this->db = $c->get('db');
      $this->_logger = $c->get('logger');
  }

  public function e404($message){
    header('HTTP/1.0 404 Not Found');
    $this->set('message',$message);
    $this->render('/errors/404');
    die();
  }
  
}
