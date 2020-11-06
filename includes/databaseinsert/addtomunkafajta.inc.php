<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$pid = $_SESSION['projektId'];
$megn = $_POST['name'];
$szulo = $_POST['csoport'];

if ($szulo == 0) {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO munkadijkoltseg (parent_id, projekt_id, munkadijkoltseg_megnevezes,
                                      munkadijkoltseg_mertekegyseg, munkadijkoltseg_mennyiseg, munkadij_id)
                                              VALUES (NULL, '$pid','$megn','',NULL,2)");
  }
  else {
    $stmt = $conn->prepare("INSERT INTO munkadijkoltseg (parent_id, projekt_id, munkadijkoltseg_megnevezes,
                                      munkadijkoltseg_mertekegyseg, munkadijkoltseg_mennyiseg, munkadij_id)
                                              VALUES (NULL, '$pid','$megn','óra',1,2)");
  }
}
else {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO munkadijkoltseg (parent_id, projekt_id, munkadijkoltseg_megnevezes,
                                      munkadijkoltseg_mertekegyseg, munkadijkoltseg_mennyiseg, munkadij_id)
                                              VALUES ('$szulo', '$pid','$megn','',NULL,2)");
  }
  else {
    $stmt = $conn->prepare("INSERT INTO munkadijkoltseg (parent_id, projekt_id, munkadijkoltseg_megnevezes,
                                      munkadijkoltseg_mertekegyseg, munkadijkoltseg_mennyiseg, munkadij_id)
                                              VALUES ('$szulo', '$pid','$megn','óra',1,2)");
  }
}
$successfullyCopied = $stmt->execute();

require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
$szoveg = ("insert munkadijkoltseg ". $megn ."");
naplozas($szoveg);

if ($successfullyCopied) {
  header("Location: ../../munkadijkoltseg.php");
  exit;
}
else {
  header("Location: ../../munkadijkoltseg.php?sikertelenfelvetel");
  exit;
}
?>
