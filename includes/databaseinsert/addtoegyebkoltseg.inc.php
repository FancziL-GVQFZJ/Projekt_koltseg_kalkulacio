<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$pid = $_SESSION['projektId'];
$megn = $_POST['name'];
$szulo = $_POST['csoport'];

// egyébköltséghez ad hozzá

if ($szulo == 0) {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (projekt_id, parent_id, egyebkoltseg_megnevezes,
                                        egyebkoltseg_mertekegyseg, egyebkoltseg_mennyiseg, munkadij_id)
                                              VALUES (?,NULL,?,'',NULL,NULL)");
    $stmt->bind_param("ss", $pid, $megn);
  }
  else {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (projekt_id, parent_id, egyebkoltseg_megnevezes,
                                        egyebkoltseg_mertekegyseg, egyebkoltseg_mennyiseg, munkadij_id)
                                              VALUES (?,NULL,?,'óra',1,2)");
    $stmt->bind_param("ss", $pid, $megn);
  }
}
else {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (projekt_id, parent_id, egyebkoltseg_megnevezes,
                                        egyebkoltseg_mertekegyseg, egyebkoltseg_mennyiseg, munkadij_id)
                                              VALUES (?,?,?,'',NULL,NULL)");
    $stmt->bind_param("sss", $pid, $szulo, $megn);
  }
  else {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (projekt_id, parent_id, egyebkoltseg_megnevezes,
                                        egyebkoltseg_mertekegyseg, egyebkoltseg_mennyiseg, munkadij_id)
                                              VALUES (?,?,?,'óra',1,2)");
    $stmt->bind_param("sss", $pid, $szulo, $megn);
  }
}
$successfullyCopied = $stmt->execute();

require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
$szoveg = ("insert egyebkoltseg ". $megn ."");
naplozas($szoveg);

if ($successfullyCopied) {

  header('Location: ../../egyebkoltseg.php');
  exit;
}
?>
