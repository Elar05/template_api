<?php

/**
 * Structure for ap API
 * @author `Elar`
 */

class App
{
  public function __construct()
  {
    /**
     *? URL Format
     ** controller/method/parameters
     ** 0 => controller
     ** 1 => method
     ** 2 => parameters
     */

    $url = $_GET['url'] ?? '';
    $url = rtrim($url, '/');
    $url = explode('/', $url);

    //? Evaluate whether position 0 exists as a file
    $fileController = 'controllers/' . $url[0] . '.php';
    if (file_exists($fileController)) {
      $nameController = 'Controllers\\' . ucfirst($url[0]);
      $controller = new $nameController($url[0]);

      //* Evaluate position 1 if controller method exists in URL
      if (isset($url[1])) {
        //? Validate if the method exists
        if (method_exists($controller, $url[1])) {
          //* Evaluate if there are parameters in the url
          if (isset($url[2])) {
            $nparam = sizeof($url);
            $params = [];
            for ($i = 2; $i < $nparam; $i++) {
              array_push($params, $url[$i]);
            }
            //? Execute the method and pass the parameters
            $controller->{$url[1]}($params);
          } else {
            //? Use the ReflectionMethod class to obtain information about the instantiated controller class and the method to execute
            $reflection = new ReflectionMethod($nameController, "{$url[1]}");
            $parameters = $reflection->getParameters();

            //! Evaluate if the method receives parameters and if there are no parameters in the url
            if (count($parameters) > 0 && empty($url[2])) {
              //? If the method receives parameters and they do not exist in the url, do not execute
              $this->response("Parameters are missing", 404);
            } else {
              //* Ejecuatar el metodo
              $controller->{$url[1]}();
            }
          }
        } else {
          //! Controller method does not exist
          $this->response("Method Not Found", 404);
        }
      } else {
        /**
         *! If there is no method in the url
         *! and we do not have a default function
         *! we show a message:
         *? $this->response("Method Not Found", 404);
         *! otherwise we execute the default function of the controller:
         *? $controller->index();
         */
        if (method_exists($controller, 'index')) {
          $controller->index();
        }
        $this->response("Method Not Found", 404);
      }
    } else {
      //! The file does not exist in the controllers folder
      $this->response("400 Not Found", 400);
    }
  }

  /**
   ** Response function
   *
   * @param string $message
   * @param integer $code
   * @return json
   */
  public function response($message, $code = 200)
  {
    http_response_code($code);
    echo json_encode(["message" => $message]);
    exit();
  }
}
