<?php
require 'dbh.inc.php';
session_start();


$pid = $_SESSION['projektId'];
$megn = $_POST['name'];
$szulo = $_POST['csoport'];

// $sql = "SELECT * FROM munkafajta
//         WHERE Megnevezes = '$szulo' AND Mennyiseg IS NULL";
// $sor=mysqli_query($conn, $sql);
//
// $row=mysqli_fetch_array($sor);
// $szulid=$row['Id'];



if ($szulo == 0) {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO munkafajta (parent_id, project_id, Megnevezes, ME, Mennyiseg, munkadij_id)
                                              VALUES (NULL, '$pid','$megn','',NULL,2)");
  }
  else {
    $stmt = $conn->prepare("INSERT INTO munkafajta (parent_id, project_id, Megnevezes, ME, Mennyiseg, munkadij_id)
                                              VALUES (NULL, '$pid','$megn','óra',1,2)");
  }
}
else {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO munkafajta (parent_id, project_id, Megnevezes, ME, Mennyiseg, munkadij_id)
                                              VALUES ('$szulo', '$pid','$megn','',NULL,2)");
  }
  else {
    $stmt = $conn->prepare("INSERT INTO munkafajta (parent_id, project_id, Megnevezes, ME, Mennyiseg, munkadij_id)
                                              VALUES ('$szulo', '$pid','$megn','óra',1,2)");
  }
}


$successfullyCopied = $stmt->execute();

require_once 'naplo.inc.php';
$szoveg = ("insert munkafajta ". $megn ."");
naplozas($szoveg);

if ($successfullyCopied) {
  header("Location: ../munkadijkoltseg.php");
  exit;
}
else {
  header("Location: ../munkadijkoltseg.php?sikertelenfelvetel");
  exit;
}
?>
