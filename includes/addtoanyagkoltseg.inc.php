<?php
require 'dbh.inc.php';
session_start();

$pid = $_SESSION['projektId'];
$megn = $_POST['name'];

$stmt = $conn->prepare("INSERT INTO anyagkoltseg (projekt_id, Megnevezes, ME, Mennyiseg, Egysegar)
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
