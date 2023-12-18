<?php

namespace Libs;

use Exception;
use Libs\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Models\AuthModel;

class Auth extends Controller
{
  public $sites;
  public $url;
  public $userId;
  public $userType;
  public $token;
  public $key;

  public function __construct($url)
  {
    $this->userType = 0;
    $this->key = "12345";
    $this->url = $url;
    $this->sites = $this->sites();
    $this->validateToken();
  }

  public function sites()
  {
    return [
      "0" => [
        'login',
      ],
      "1" => [
        'user', 'logout'
      ],
      "2" => [
        'main',
      ],
    ];
  }

  public function validateToken()
  {
    if ($this->existsToken()) {
      //! We validate based on the token data
      if (!$this->isAuthorized($this->url, $this->userType)) {
        $this->response(['message' => "Don't have permission"], 401);
      }
    } else {
      //! We validate based on public endpoints
      if (!$this->isAuthorized($this->url, $this->userType)) {
        $this->response(['message' => "Don't have permission"], 401);
      }
    }
  }

  public function existsToken()
  {
    //* Get the sent headers
    $headers = apache_request_headers();

    if (!isset($headers['Authorization'])) {
      $this->response(['message' => 'Token required'], 401);
    }

    $token = str_replace("Bearer ", "", $headers['Authorization']);

    try {
      $decoded = JWT::decode($token, new Key($this->key, 'HS256'));

      $auth = new AuthModel();
      if ($auth->existsToken($token)) {
        $user = $auth->getUser($decoded->data->id);
        $this->userId = $user['id'];
        $this->userType = $user['type_id'];
        return true;
      }

      $this->response(['message' =>  "Token not exists"], 400);
    } catch (Exception $e) {
      $this->response(['message' =>  "Token invalid"], 400);
    }
  }

  public function initialize($user)
  {
    $this->token = $this->generateToken($user);
    $auth = new AuthModel();
    $auth->updateToken($this->token, $user['id']);

    $this->response(["token" => $this->token, "data" => $user]);
  }

  public function isAuthorized($view, $tipo)
  {
    // return in_array($view, $this->sites); // Desde bd
    return in_array($view, $this->sites[$tipo]); // En codigo
  }

  public function generateToken($user)
  {
    $time = time();
    $token = [
      "iat" => $time,
      "exp" => $time * 60 * 60,
      "data" => ["id" => $user['id'], "email" => $user['email']]
    ];

    return JWT::encode($token, $this->key, 'HS256');
  }

  public function logout()
  {
    $auth = new AuthModel();
    $auth->deleteToken($this->userId);
  }
}
