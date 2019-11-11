<?php
  require "vendor/autoload.php";

  echo "Hello, cruel world!";

  $params = array(
    "foo" => "bar",
    "baz" => "qux",
    "bar" => TRUE,
    "qux" => array(
      "a" => 123,
      "b" => 456
    )
  );

  $result = Ppm\Client::postOrder("orders", $params);

  echo $result;

  echo "Goodbye, cruel world!";

?>
