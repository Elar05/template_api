<?php

namespace Controllers;

use Libs\Auth;
use Models\UserModel;

class User extends Auth
{
  public function __construct($url)
  {
    parent::__construct($url);
  }

  public function index()
  {
    $user = new UserModel();
    $this->response(["users" => $user->getAll()]);
  }

  public function create()
  {
    if (!$this->existsPOST(['nombres', 'telefono', 'email', 'password'])) {
      $this->response(["error" => "Faltan parametros"]);
    }

    $user = new UserModel();
    $user->names = $_POST['names'];
    $user->phone = $_POST['phone'];
    $user->email = $_POST['email'];
    $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT, ["cost" => 10]);

    if ($user->save())
      $this->response(["success" => "user registrado"]);

    $this->response(["error" => "Error al registrar user"]);
  }

  public function get()
  {
    if (!$this->existsPOST(['id'])) {
      $this->response(["error" => "Faltan parametros"]);
    }

    $user = new UserModel();
    if ($user = $user->get($_POST['id'])) {
      unset($user["password"]);
      $this->response(["success" => "user encontrado", "user" => $user]);
    } else {
      $this->response(["error" => "Error al buscar user"]);
    }
  }

  public function edit()
  {
    if (!$this->existsPOST(['id', 'nombres', 'telefono', 'email'])) {
      $this->response(["error" => "Faltan parametros"]);
    }

    $user = new UserModel();
    $user->id = $_POST['id'];
    $user->names = $_POST['names'];
    $user->phone = $_POST['phone'];
    $user->email = $_POST['email'];

    if ($user->update()) {
      if ($this->existsPOST(['password'])) {
        $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT, ["cost" => 10]);
        $user->updatePassword();
      }

      $this->response(["success" => "user actualizado"]);
    }

    $this->response(["error" => "Error al actualizar user"]);
  }

  public function delete()
  {
    if (!$this->existsPOST(['id'])) {
      $this->response(["error" => "Faltan parametros"]);
    }

    $user = new UserModel();
    if ($user->delete($_POST['id'])) {
      $this->response(["success" => "user eliminado"]);
    }
    $this->response(["error" => "Error al eliminar user"]);
  }
}
