<?php
require 'dbh.inc.php';
session_start();


$pid = $_SESSION['projektId'];
$megn = $_POST['name'];
$szulo = $_POST['szulo'];

$sql = "SELECT * FROM egyebkoltseg
        WHERE Megnevezes = '$szulo' AND Mennyiseg IS NULL";
$sor=mysqli_query($conn, $sql);

$row=mysqli_fetch_array($sor);
$szulid=$row['Id'];


if (empty($szulo)) {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (parent_id, project_id, Megnevezes, ME, Mennyiseg, munkadij_id)
                                              VALUES (NULL, '$pid','$megn','',NULL,2)");
  }
  else {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (parent_id, project_id, Megnevezes, ME, Mennyiseg, munkadij_id)
                                              VALUES (NULL, '$pid','$megn','óra',1,2)");
  }
}
else {
  if (isset($_POST['cim'])) {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (parent_id, project_id, Megnevezes, ME, Mennyiseg, munkadij_id)
                                              VALUES ('$szulid', '$pid','$megn','',NULL,2)");
  }
  else {
    $stmt = $conn->prepare("INSERT INTO egyebkoltseg (parent_id, project_id, Megnevezes, ME, Mennyiseg, munkadij_id)
                                              VALUES ('$szulid', '$pid','$megn','óra',1,2)");
  }
}


$successfullyCopied = $stmt->execute();

require_once 'naplo.inc.php';
$szoveg = ("insert egyebkoltseg ". $megn ."");
naplozas($szoveg);

if ($successfullyCopied) {
  header("Location: ../egyebkoltseg.php");
  exit;
}
?>
