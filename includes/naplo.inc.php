<?php
require 'dbh.inc.php';
session_start();

function naplozas($szoveg2){
  require 'dbh.inc.php';
  $fid = $_SESSION['userId'];
  $fnev = $_SESSION['userUid'];
  $pid = $_SESSION['projektId'];
  $pnev = $_SESSION['projektNeve'];
  $jogosultsag = $_SESSION['jogosultsag'];

  $szoveg1 = $fid . ";" . $fnev . ";" . $pid . ";" . $pnev . ";" . $jogosultsag;
  $szoveg3 = $szoveg1 . ";" . $szoveg2;

  $stmt = $conn->prepare("INSERT INTO naplo (Datum, Szoveg)
                 VALUES (CURRENT_TIMESTAMP(), '$szoveg3')");

  $successfullyCopied = $stmt->execute();
}


?>
