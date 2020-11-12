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
                                              VALUES (NULL, ?,?,'',NULL,2)");
    $stmt->bind_param("ss", $pid, $megn);
  }
  else {
    $stmt = $conn->prepare("INSERT INTO munkadijkoltseg (parent_id, projekt_id, munkadijkoltseg_megnevezes,
                                      munkadijkoltseg_mertekegyseg, munkadijkoltseg_mennyiseg, munkadij_id)
                                              VALUES (NULL, ?,?,'óra',1,2)");
    $stmt->bind_param("ss", $pid, $megn);
  }
}
else {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO munkadijkoltseg (parent_id, projekt_id, munkadijkoltseg_megnevezes,
                                      munkadijkoltseg_mertekegyseg, munkadijkoltseg_mennyiseg, munkadij_id)
                                              VALUES (?,?,?,'',NULL,2)");
    $stmt->bind_param("sss", $szulo, $pid, $megn);
  }
  else {
    $stmt = $conn->prepare("INSERT INTO munkadijkoltseg (parent_id, projekt_id, munkadijkoltseg_megnevezes,
                                      munkadijkoltseg_mertekegyseg, munkadijkoltseg_mennyiseg, munkadij_id)
                                              VALUES (?,?,?,'óra',1,2)");
    $stmt->bind_param("sss", $szulo, $pid, $megn);
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
