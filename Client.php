<?php
namespace Ppm\Fulfillment;
class Client {
  public static function postOrder($params = array(), $ppmApiKey, $url){
    $process = curl_init();

    // Rework Params into a usable JSON object
    foreach($params as $k => $v) {
      if(is_array($v) || is_Object($v)) {
        $params[$k] = json_encode($v);
      } elseif (is_bool($v)) {
        $params[$k] = $v ? "true" : "false";
      }

      $curlOptions = array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => [
          "Authorization: Bearer ". $ppmApiKey,
          "Content-Type: application/json"
        ],
        CURLOPT_POST => TRUE,
        CURLOPT_POSTFIELDS => json_encode($params)
      );

      curl_setopt_array($process, $curlOptions);
      $resultBody = curl_exec($process);
      $success = "true";

      if(!curl_errno($process)) {
        switch($http_code = curl_getinfo($process, CURLINFO_RESPONSE_CODE)) {
        case 200:
        case 201:
          break;
        default:
          $success = "false";
        }
      }

      curl_close($process);
      return array(
        "body" => $resultBody,
        "success" => $success
      );
    }
  }
}
