<?php
  require_once "vendor/autoload.php";

  /* Testing for client */
  echo "Doing a thing";

  Ppm\Magento\Client::postOrder("some path");

  echo "Done doing the thing";
?>
