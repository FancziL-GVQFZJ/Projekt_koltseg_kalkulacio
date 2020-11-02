<?php
require 'kapcsolat.inc.php';
session_start();

function naplozas($szoveg2){
  require 'kapcsolat.inc.php';
  $fid = $_SESSION['userId'];
  $fnev = $_SESSION['userUid'];
  $pid = $_SESSION['projektId'];
  $pnev = $_SESSION['projektNeve'];
  $jogosultsag = $_SESSION['jogosultsag'];

  $szoveg1 = $fid . ";" . $fnev . ";" . $pid . ";" . $pnev . ";" . $jogosultsag;
  $szoveg3 = $szoveg1 . ";" . $szoveg2;

  $stmt = $conn->prepare("INSERT INTO naplo (naplo_datum, naplo_esemeny)
                 VALUES (CURRENT_TIMESTAMP(), '$szoveg3')");

  $successfullyCopied = $stmt->execute();
}


?>
