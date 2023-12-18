<?php

namespace Models;

use PDO;
use Libs\Model;
use PDOException;

class AuthModel extends Model
{
  public function __construct()
  {
    parent::__construct();
  }

  public function updateToken($token, $iduser)
  {
    try {
      $query = $this->prepare("UPDATE users SET token = :token WHERE id = :id;");

      return $query->execute([
        'id' => $iduser,
        'token' => $token,
      ]);
    } catch (PDOException $e) {
      error_log("AuthModel::updateToken() -> " . $e->getMessage());
      return false;
    }
  }

  public function existsToken($token)
  {
    try {
      $query = $this->prepare("SELECT token FROM users WHERE token = :token;");
      $query->execute(['token' => $token]);
      if ($query->rowCount() > 0) return true;
    } catch (PDOException $e) {
      error_log("AuthModel::existsToken() -> " . $e->getMessage());
      return false;
    }
  }

  public function getUser($id)
  {
    try {
      $query = $this->prepare("SELECT * FROM users WHERE id = :id;");
      $query->execute(['id' => $id]);
      return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("AuthModel::existsToken() -> " . $e->getMessage());
      return false;
    }
  }

  public function deleteToken($id)
  {
    try {
      $query = $this->prepare("UPDATE users SET token = '' WHERE id = :id;");
      $query->execute(['id' => $id]);
      return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("AuthModel::deleteToken() -> " . $e->getMessage());
      return false;
    }
  }
}
