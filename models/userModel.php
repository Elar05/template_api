<?php

namespace Models;

use PDO;
use PDOException;
use Libs\Model;

class UserModel extends Model
{
  public $id;
  public $names;
  public $phone;
  public $email;
  public $password;
  public $token;

  public function __construct()
  {
    parent::__construct();
  }

  public function get($id, $colum = "id")
  {
    try {
      $query = $this->prepare("SELECT * FROM users WHERE $colum = ?;");
      $query->execute([$id]);
      return $query->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("UserModel::get() -> " . $e->getMessage());
      return false;
    }
  }

  public function getAll($colum = null, $value = null)
  {
    try {
      $sql = "";
      if ($colum !== null) $sql = " WHERE $colum = '$value'";

      $query = $this->query("SELECT * FROM users $sql;");
      $query->execute();
      return $query->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("UserModel::getAll() -> " . $e->getMessage());
      return false;
    }
  }

  public function save()
  {
    try {
      $query = $this->prepare("INSERT INTO users(names, phone, email, password, token) VALUES (:names, :phone, :email, :password, :token);");

      $query->bindParam(':names', $this->names, PDO::PARAM_STR);
      $query->bindParam(':phone', $this->phone, PDO::PARAM_STR);
      $query->bindParam(':email', $this->email, PDO::PARAM_STR);
      $query->bindParam(':password', $this->password, PDO::PARAM_STR);
      $query->bindParam(':token', $this->token, PDO::PARAM_STR);

      return $query->execute();
    } catch (PDOException $e) {
      error_log("UserModel::save() -> " . $e->getMessage());
      return false;
    }
  }

  public function update()
  {
    try {
      $query = $this->prepare("UPDATE users SET names = :names, phone = :phone, email = :email, password = :password WHERE id = :id;");

      return $query->execute([
        'id' => $this->id,
        'names' => $this->names,
        'phone' => $this->phone,
        'email' => $this->email,
        'password' => $this->password,
      ]);
    } catch (PDOException $e) {
      error_log("UserModel::update() -> " . $e->getMessage());
      return false;
    }
  }

  public function updatePassword()
  {
    try {
      $query = $this->prepare("UPDATE users SET password = :password WHERE id = :id;");

      return $query->execute([
        'id' => $this->id,
        'password' => $this->password,
      ]);
    } catch (PDOException $e) {
      error_log("UserModel::updatePassword() -> " . $e->getMessage());
      return false;
    }
  }

  public function delete($id)
  {
    try {
      $query = $this->prepare("DELETE FROM users WHERE id = ?;");
      $query->execute([$id]);
      return true;
    } catch (PDOException $e) {
      error_log("UserModel::delete() -> " . $e->getMessage());
      return false;
    }
  }
}
