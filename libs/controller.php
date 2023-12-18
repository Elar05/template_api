<?php

namespace Libs;

class Controller
{
  public function redirect($url, $mensajes = [])
  {
    $data = [];
    $params = '';

    foreach ($mensajes as $key => $value) {
      array_push($data, $key . '=' . $value);
    }
    $params = join('&', $data);

    if ($params != '') {
      $params = '?' . $params;
    }
    header('Location: ' . URL . "/$url$params");
    exit();
  }

  public function response($data, $code = 200)
  {
    http_response_code($code);
    echo json_encode($data);
    exit();
  }

  public function existsPOST($params)
  {
    foreach ($params as $param) {
      if (!isset($_POST[$param])) return false;
    }
    return true;
  }

  public function existsGET($params)
  {
    foreach ($params as $param) {
      if (!isset($_GET[$param])) return false;
    }
    return true;
  }
}
