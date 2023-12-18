<?php

namespace Models;

use PDO;
use Libs\Model;
use PDOException;

class LoginModel extends Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function login($email, $password)
  {
    try {
      $query = $this->prepare("SELECT * FROM users WHERE email = :email;");
      $query->execute(["email" => $email]);

      if ($query->rowCount() == 1) {
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if (password_verify($password, $user['password'])) {
          return $user;
        }

        return NULL;
      }
    } catch (PDOException $e) {
      error_log("LoginModel::login() -> " . $e->getMessage());
      return false;
    }
  }
}
