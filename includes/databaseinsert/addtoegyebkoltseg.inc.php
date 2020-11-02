<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$pid = $_SESSION['projektId'];
$megn = $_POST['name'];
$szulo = $_POST['szulo'];

$sql = "SELECT * FROM egyebkoltseg
        WHERE egyebkoltseg_megnevezes = '$szulo' AND egyebkoltseg_mennyiseg IS NULL";
$sor=mysqli_query($conn, $sql);

$row=mysqli_fetch_array($sor);
$szulid=$row['egyebkoltseg_id'];


if (empty($szulo)) {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (projekt_id, parent_id, egyebkoltseg_megnevezes,
                                        egyebkoltseg_mertekegyseg, egyebkoltseg_mennyiseg, munkadij_id)
                                              VALUES ('$pid',NULL,'$megn','',NULL,2)");
  }
  else {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (projekt_id, parent_id, egyebkoltseg_megnevezes,
                                        egyebkoltseg_mertekegyseg, egyebkoltseg_mennyiseg, munkadij_id)
                                              VALUES ('$pid',NULL,'$megn','óra',1,2)");
  }
}
else {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (projekt_id, parent_id, egyebkoltseg_megnevezes,
                                        egyebkoltseg_mertekegyseg, egyebkoltseg_mennyiseg, munkadij_id)
                                              VALUES ('$pid','$szulid','$megn','',NULL,2)");
  }
  else {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (projekt_id, parent_id, egyebkoltseg_megnevezes,
                                        egyebkoltseg_mertekegyseg, egyebkoltseg_mennyiseg, munkadij_id)
                                              VALUES ('$pid','$szulid','$megn','óra',1,2)");
  }
}
$successfullyCopied = $stmt->execute();

require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
$szoveg = ("insert egyebkoltseg ". $megn ."");
naplozas($szoveg);

if ($successfullyCopied) {
  header("Location: ../egyebkoltseg.php");
  exit;
}
?>
