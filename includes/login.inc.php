<?php
if (isset($_POST['bejelentkezes'])) {
  require 'kapcsolat.inc.php';

  // a megadott adatok ellenőrzése után bejelentkezést enged a rendszerbe

  $fnev = $_POST['felhasznalo'];
  $jszo = $_POST['jelszo'];

  if (empty($fnev) || empty($jszo)) {
    header("Location: ../index.php?error=ures_valamelyik_mezo");
    exit();
  }
  else {
    $sql = "SELECT * FROM felhasznalo WHERE felhasznalo_nev=?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header("Location: ../index.php?nemsikerult_a_csatlakozas");
      exit();
    }
    else {
      mysqli_stmt_bind_param($stmt, "s", $fnev);
      mysqli_stmt_execute($stmt);
      $results = mysqli_stmt_get_result($stmt);

      if ($row = mysqli_fetch_assoc($results)) {
        $pwdCheck = password_verify($jszo, $row['felhasznalo_jelszo']);

        if ($pwdCheck == false) {
          header("Location: ../index.php?hibas_jelszo");
          exit();
        }
        elseif ($pwdCheck == true) {
          session_start();
          $_SESSION['userId'] = $row['felhasznalo_id'];
          $_SESSION['userName'] = $row['felhasznalo_nev'];

          header("Location: ../index.php?sikeres_bejelentkezes");
          exit();
        }
        else {
          header("Location: ../index.php?hibas_jelszo");
          exit();
        }
      }
      else {
        header("Location: ../index.php?error=nincs_ilyen_felhasznalo");
        exit();
      }
    }
  }
}
else {
  header("Location: ../index.php");
  exit();
}
