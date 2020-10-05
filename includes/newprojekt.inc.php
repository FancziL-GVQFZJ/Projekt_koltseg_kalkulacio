<?php
  if (isset($_POST['newprojekt'])) {
    require 'dbh.inc.php';
    session_start();


    $projektneve = $_POST['projektnev'];
    $fid = $_SESSION['userId'];

    if (($projektneve != null) && ($projektneve != "")) {


      $stmt = $conn->prepare("INSERT INTO projekt (projektNev)
                                          VALUES ('$projektneve')");
      $successfullyCopied = $stmt->execute();

      if ($successfullyCopied) {

        $id = $conn -> insert_id;

        $stmt2 = $conn->prepare("INSERT INTO pf_kapcsolat (userId, projektId)
                                                  VALUES ('$fid','$id')");
        $successfullyCopied2 = $stmt2->execute();

        require_once 'naplo.inc.php';
        $szoveg = ("insert projekt ". $projektneve . "");
        naplozas($szoveg);

        if ($successfullyCopied2)
        {
          header("Location: ../index.php?sikeres_felvetel");
          exit();
        }
        else
        {
          header("Location: ../index.php?2tablasikertelenosszekapcsolasa");
          exit();
        }
      }
      header("Location: ../index.php?error=nemjovalami");
      exit();
    }
    header("Location: ../index.php?error=adjonmegnevet");
    exit();

  }
?>
