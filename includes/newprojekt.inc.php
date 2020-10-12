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

        if ($successfullyCopied2) {
          $dij=mysqli_query($conn,"SELECT * FROM munkadij");
          while ($row=mysqli_fetch_array($dij)){
            $mid=$row['Id'];
            $munkafajta=$row['MunkaFajta'];
            $oraber=$row['Oraber'];
            $stmt3 = $conn->prepare("INSERT INTO projektmunkadij (Munkadij_id, Projekt_id, pm_MunkaFajta, pm_Oraber)
                                                      VALUES ('$mid','$id','$munkafajta','$oraber')");
            $successfullyCopied3 = $stmt3->execute();
          }
        }

        require_once 'naplo.inc.php';
        $szoveg = ("insert projekt ". $projektneve . "");
        naplozas($szoveg);

        if ($successfullyCopied3)
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
