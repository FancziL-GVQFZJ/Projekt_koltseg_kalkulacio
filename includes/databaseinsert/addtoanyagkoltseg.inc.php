<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$pid = $_SESSION['projektId'];
$megn = $_POST['name'];

// anyagköltséghez ad hozzá

$stmt = $conn->prepare("INSERT INTO anyagkoltseg (projekt_id, anyagkoltseg_megnevezes,
  anyagkoltseg_mertekegyseg, anyagkoltseg_mennyiseg, anyagkoltseg_egysegar)
                                          VALUES (?,?,'db','1','1')");
$stmt->bind_param("ss", $pid, $megn);
$successfullyCopied = $stmt->execute();

require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
$szoveg = ("insert anyagkoltseg ". $megn ."");
naplozas($szoveg);

if ($successfullyCopied) {
  header("Location: ../../anyagkoltseg.php");
  exit();
}
else {
  header("Location: ../../anyagkoltseg.php?nemsikerultafelvetel");
  exit();
}
?>
