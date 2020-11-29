<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$pid = $_SESSION['projektId'];
$megn = $_POST['name'];
$ora = $_POST['oraber'];

// projektmunkadíjhoz ad hozzá

if(is_numeric($ora) && $ora > 0 && $ora == round($ora, 0)){
  $dij=mysqli_query($conn,"SELECT MAX(munkadij_id) AS max FROM projektmunkadij WHERE projekt_id='$pid'");
  $row=mysqli_fetch_array($dij);
  $kszam=$row['max'];
  $kszam=$kszam+1;

  $stmt = $conn->prepare("INSERT INTO projektmunkadij (projekt_id, munkadij_id, projektmunkadij_munkafajta, projektmunkadij_oraber)
                                            VALUES (?,?,?,?)");
  $stmt->bind_param("ssss", $pid, $kszam, $megn, $ora);
  $successfullyCopied = $stmt->execute();

  require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
  $szoveg = ("insert projektmunkadij ". $megn ."");
  naplozas($szoveg);

  if ($successfullyCopied) {
    header("Location: ../../projektmunkadij.php");
    exit();
  }else {
    header("Location: ../../projektmunkadij.php?error=nemsikerultafelvetel");
    exit();
  }
}else {
  header("Location: ../../projektmunkadij.php?error=nemsikerultafelvetel");
  exit();
}


?>
