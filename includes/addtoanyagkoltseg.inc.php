<?php
require 'dbh.inc.php';
session_start();

$pid = $_SESSION['projektId'];
$megn = $_POST['name'];

$stmt = $conn->prepare("INSERT INTO anyagkoltseg (projekt_id, anyagkoltseg_megnevezes,
  anyagkoltseg_mertekegyseg, anyagkoltseg_mennyiseg, anyagkoltseg_egysegar)
                                          VALUES ('$pid','$megn','db','1','1')");
$successfullyCopied = $stmt->execute();

require_once 'naplo.inc.php';
$szoveg = ("insert anyagkoltseg ". $megn ."");
naplozas($szoveg);

if ($successfullyCopied) {
  header("Location: ../anyagkoltseg.php");
  exit;
}
?>
