<?php

namespace Controllers;

use Libs\Auth;

class Logout extends Auth
{
  public function __construct($url)
  {
    parent::__construct($url);
  }

  public function index()
  {
    $this->logout();
    $this->response(["message" => "Logged out"]);
  }
}
