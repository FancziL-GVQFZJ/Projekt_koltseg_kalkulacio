<?php
  if (isset($_POST['newprojekt'])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
    session_start();

    $projektneve = $_POST['projektnev'];
    $fid = $_SESSION['userId'];

    //az új projekteket menti az adatbázisba
    if (($projektneve != null) && ($projektneve != "")) {
      $stmt = $conn->prepare("INSERT INTO projekt (projekt_nev)
                                          VALUES ('$projektneve')");
      $successfullyCopied = $stmt->execute();

      if ($successfullyCopied) {

        $id = $conn -> insert_id;
        $stmt2 = $conn->prepare("INSERT INTO pf_kapcsolat (felhasznalo_id, projekt_id)
                                                  VALUES ('$fid','$id')");
        $successfullyCopied2 = $stmt2->execute();

        if ($successfullyCopied2) {
          $dij=mysqli_query($conn,"SELECT * FROM munkadij");
          while ($row=mysqli_fetch_array($dij)){
            $mid=$row['munkadij_id'];
            $munkafajta=$row['munkadij_fajta'];
            $oraber=$row['munkadij_oraber'];
            $stmt3 = $conn->prepare("INSERT INTO projektmunkadij (projekt_id, munkadij_id,
                                      projektmunkadij_munkafajta, projektmunkadij_oraber)
                                                      VALUES ('$id','$mid','$munkafajta','$oraber')");
            $successfullyCopied3 = $stmt3->execute();
          }
        }

        require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
        $szoveg = ("insert projekt ". $projektneve . "");
        naplozas($szoveg);

        if ($successfullyCopied3)
        {
          header("Location: ../../index.php?sikeres_felvetel");
          exit();
        }
        else
        {
          header("Location: ../../index.php?2tablasikertelenosszekapcsolasa");
          exit();
        }
      }
      header("Location: ../../index.php");
      exit();
    }
    header("Location: ../../index.php?error=adjonmegnevet");
    exit();

  }
?>
