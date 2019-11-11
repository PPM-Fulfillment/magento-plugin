<?php
namespace Ppm;
class Client {

  public static $apiBaseUrl = "http://localhost:3000/";

  public static function postOrder($path = "orders", $params = array()){
    $process = curl_init();

    // Rework Params into a usable JSON object
    foreach($params as $k => $v) {
      if(is_array($v) || is_Object($v)) {
        $params[$k] = json_encode($v);
      } elseif (is_bool($v)) {
        $params[$k] = $v ? 'true' : 'false';
      }

      $curlOptions = array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => self::$apiBaseUrl . $path,
        CURLOPT_HTTPHEADER => [
          'Authorization: Basic '. base64_encode("foo" . ":"),
          'Content-Type: application/json'
        ],
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => json_encode($params)
      );

      curl_setopt_array($process, $curlOptions);
      $result = curl_exec($process);
      curl_close($process);
      return $result;
    }
  }
}
?>
