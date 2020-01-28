<?php
namespace Ppm\Fulfillment;
class ErrorMailer {

  public static function send($to, $params, $message) {
    $subject = "Error processing order";
    $body = self::buildErrorBody($params, $message);

    self::dispatch($to, $subject, $body);
  }

  private static function buildErrorBody($params = array(), $message = "") {
    $paramsBody = json_encode($params);
    $body = "There was an error processing this order:" .
      "\n\n" .
      $message .
      "\n\n" .
      $paramsBody .
      "\n\n" .
      "Please call PPM to resolve.";

    return $body;
  }

  private static function dispatch($to, $subject, $message) {
    $headers = "From: noreply@ppm.com" . "\r\n";

    mail($to, $subject, $message, $headers);
  }
}
