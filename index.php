<?php
  require "vendor/autoload.php";

  echo "Hello, cruel world!\n\n";

  $params = array(
    "foo" => "bar",
    "baz" => "qux",
    "bar" => TRUE,
    "qux" => array(
      "a" => 123,
      "b" => 456
    )
  );

  $result = Ppm\MagentoFulfillment\Client::postOrder("orders", $params);

  echo $result["success"] . "\n";
  echo $result["body"];

  Ppm\MagentoFulfillment\ErrorMailer::send("andrewek@gmail.com", $params, "A failure!");

  echo "\n\nGoodbye, cruel world!";
?>
