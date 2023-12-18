<?php

namespace Controllers;

use Libs\Auth;
use Models\LoginModel;

class Login extends Auth
{
  public $model;

  public function __construct($url)
  {
    parent::__construct($url);
    $this->model = new LoginModel;
  }

  public function auth()
  {
    if (!$this->existsPOST(['email', 'password'])) {
      $this->response(['message' => 'No Found Parameters'], 400);
    }

    $user = $this->model->login($_POST['email'], $_POST['password']);

    if ($user !== NULL) {
      $this->initialize($user);
    } else {
      $this->response(['message' => 'Error in email or password'], 400);
    }
  }
}
