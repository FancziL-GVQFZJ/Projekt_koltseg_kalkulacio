<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$megn = $_POST['name'];
$ora = $_POST['oraber'];

// munkadijakhoz ad hozzá

if(is_numeric($ora) && $ora > 0 && $ora == round($ora, 0)){
  $stmt = $conn->prepare("INSERT INTO munkadij (munkadij_fajta, munkadij_oraber)
                                            VALUES (?,?)");
  $stmt->bind_param("ss", $megn, $ora);
  $successfullyCopied = $stmt->execute();

  require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
  $szoveg = ("insert munkadij ". $megn ."");
  naplozas($szoveg);

  if ($successfullyCopied) {
    header("Location: ../../munkadijak.php");
    exit();
  }else {
    header("Location: ../../munkadijak.php?error=nemsikerultafelvetel");
    exit();
  }
}else {
  header("Location: ../../munkadijak.php?error=nemsikerultafelvetel");
  exit();
}


?>
