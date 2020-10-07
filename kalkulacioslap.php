<?php
    $thisPage='Kalkulacioslap';
    require "header.php";
    session_start();
?>
<style><?php include 'css/navbar.css';?></style>
<style><?php include 'css/table.css';?></style>


<div id="container">
  <div id="main">
    <main>
      <div>
        <?php
        require 'includes/dbh.inc.php';
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId'])) { ?>
          <nav class="topnav">
            <ul>
              <?php if ($jogosultsag == 'iras' || $jogosultsag == 'admin'){ ?>
                <li><a href="munkadijkalkulacio.php">Munkadíj költség</a></li>
                <li><a href="egyebkoltseg.php">Egyéb költség</a></li>
                <li><a href="muszakitartalom.php">Műszaki tartalom</a></li>
              <?php } ?>
              <li><a style="background-color: #ddd;" href='#'>Kalkulációs Lap</a></li>
              <li><a href="nyomtatasilap.php">Nyomtatási Lap</a></li>
            </ul>
          </nav>

          <?php
          $pid = $_SESSION['projektId'];
          $sql = "SELECT * FROM alkatresz
                INNER JOIN pa_kapcsolat
                  ON alkatresz.id = pa_kapcsolat.alkatresz_id
                INNER JOIN projekt
                  ON pa_kapcsolat.projekt_id = projekt.idProjekt
                  WHERE projekt.idProjekt = $pid
                  ORDER BY alkatresz.id";

          $sor=mysqli_query($conn, $sql);

          $checkRecord1 = mysqli_query($conn,"SELECT * FROM pa_kapcsolat WHERE projekt_id = '$pid'");
          $totalrows1 = mysqli_num_rows($checkRecord1);

          if ($totalrows1 > 0) {
            echo "<div align= \"center\" id=\"nyomtatas\">";
              echo "<p style='font-size: large;'><b><u>Villamos anyaglista</u></b></p>";
              echo "<table border=1; style='border-collapse: collapse;' id='KosarTable'>";
              echo "<tr class='fejlec'>";
              echo "<th>id</th><th>Megnevezés</th><th>SAPSzám</th><th>Mérték egység</th><th>Egységár</th><th>Darabszám</th>";
              /*for($i=0;$i<10;$i++)
              {
                echo "<td>".mysqli_fetch_field_direct($sor, $i)->name."</td>";
              }*/
              echo "<td><p>ÁR összesen</p></td>";
              $i=1;
              while ($row=mysqli_fetch_array($sor))
              {
                $sorid=$row['alkatresz_id'];
                echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td>".$row['Megnevezes']."</td>";
                echo "<td>".$row['SAPSzam']."</td>";
                echo "<td>".$row['ME']."</td>";
                echo "<td>".$row['Egysegar']."</td>";
                echo "<td>".$row['DBszam']."</td>";
                $sorar=$row['Egysegar']*$row['DBszam'];
                echo "<td>".$sorar."</td>";
                $osszegar=$osszegar+$sorar;
                echo "</tr>";
                $i++;

              }
              echo  "<tr>";
              echo  "<td></td>";
              echo  "<td colspan='5' align='right'>Teljes ár:</td>";
              echo  "<td align='left'>$osszegar</td>";
              echo  "</tr>";
              echo  "</table>";
          }
          
          $checkRecord2 = mysqli_query($conn,"SELECT * FROM munkafajta WHERE project_id = '$pid'");
          $totalrows2 = mysqli_num_rows($checkRecord2);

          if ($totalrows2 > 0) {
            $mernokmido=0;
            $muszereszmido=0;

            echo "<p style='font-size: large;'><b><u>Munkadíj költség</u></b></p>";
            echo "<table border=1; style='border-collapse: collapse;' id='Munkadijkalkulacio'>";
            echo "<tr class='fejlec'>";
            echo "<th>Megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Órabér</th><th>Ár:</th>";
            $query="SELECT * FROM munkafajta WHERE parent_id IS NULL AND project_id = '$pid'";
            $parents=mysqli_query($conn,$query);

            while ($row=mysqli_fetch_array($parents))
            {?>
              <tr>
              <?php
              echo "<td><b>".$row['Megnevezes']."</b></td>";
              echo "<td>".$row["ME"]."</td>";
              echo "<td>".$row["Mennyiseg"]."</td>";
              echo "<td></td><td></td>";
              $sorid=$row['Id'];?>
              </tr>
              <?php
              $arresz = show_children($row['Id']);
              $teljesar=$teljesar+$arresz;
            }
            echo  "<tr>";
            echo  "<td align='left'>Mérnöki munkaidő:</td>";
            echo  "<td>óra</td>";
            echo  "<td align='left'>$mernokmido</td>";
            echo  "<td></td><td></td>";
            echo  "</tr>";
            echo  "<tr>";
            echo  "<td align='left'>Szerelői minkaidő:</td>";
            echo  "<td>óra</td>";
            echo  "<td align='left'>$muszereszmido</td>";
            echo  "<td></td><td></td>";
            echo  "</tr>";
            echo  "<tr>";
            echo  "<td></td>";
            echo  "<td colspan='3' align='right'>Teljes ár:</td>";
            echo  "<td align='left'>$teljesar</td>";
            echo  "</tr>";
            print "</table>";


            echo "<p style='font-size: large;'><b><u>Egyéb költség</u></b></p>";
            echo "<table border=1; style='border-collapse: collapse;' id='Egyebkoltsegkalkulacio'>";
            echo "<tr class='fejlec'>";
            echo "<th>Megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Órabér</th><th>Ár:</th>";
          }

          $checkRecord3 = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE project_id = '$pid'");
          $totalrows3 = mysqli_num_rows($checkRecord3);

          if ($totalrows3 > 0) {
            $query="SELECT * FROM egyebkoltseg WHERE parent_id IS NULL AND project_id = '$pid'";
            $parents=mysqli_query($conn,$query);

            while ($row=mysqli_fetch_array($parents))
            { ?>

              <tr>
              <?php
              echo "<td><b>".$row['Megnevezes']."</b></td>";
              echo "<td>".$row["ME"]."</td>";
              echo "<td>".$row["Mennyiseg"]."</td>";
              echo "<td></td><td></td>";
              $sorid=$row['Id'];?>
              </tr>
              <?php
              $arresz = show_children2($row['Id']);
              $teljesar=$teljesar+$arresz;
            }
            echo  "<tr>";
            echo  "<td></td>";
            echo  "<td colspan='3' align='right'>Teljes ár:</td>";
            echo  "<td align='left'>$teljesar</td>";
            echo  "</tr>";
            print "</table>";
          }

          $checkRecord4 = mysqli_query($conn,"SELECT * FROM muszakitartalom WHERE projekt_id = '$pid'");
          $totalrows4 = mysqli_num_rows($checkRecord4);

          if ($totalrows4 > 0) {
            $result = mysqli_query($conn,"SELECT * FROM muszakitartalom where projekt_id='$pid'");
            $row = mysqli_fetch_array($result);
            $leiras=$row['tartalom'];
            echo "<p style='font-size: large;'><b><u>Műszaki tartalom</u></b></p>";
             echo '<div style="border:solid 1px #000"; id="mszt";>';
              echo htmlspecialchars_decode($leiras);
             echo "</div>";
            echo "</div>";
          }
        }
        else {
          echo '<p>You are logged out!</p>';
        }
        ?>

      </div>
    </main>
  </div>
</div>
<?php

function show_children($parentID, $depth=1){
  require 'includes/dbh.inc.php';
  global $mernokmido,$muszereszmido;

  $children = mysqli_query($conn,"SELECT * FROM munkafajta WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['Id'];?>
    <tr>
    <?php if ($row['Mennyiseg']==NULL) {

      echo "<td><b>".str_repeat("&nbsp;", $depth * 5).$row['Megnevezes']."</b></td>";
      echo "<td></td><td></td><td></td><td></td>";?>

      <?php echo "</tr>";
      $arresz = show_children($row['Id'], $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM munkadij
                    INNER JOIN munkafajta
                    ON munkadij.Id = munkafajta.munkadij_id
                    WHERE munkafajta.Id ='$sorid'");
      $row2=mysqli_fetch_array($munkadij);
      if ($row2['MunkaFajta']=='Mérnök') {
        $mernokmido=$mernokmido+$row['Mennyiseg'];
      }
      elseif ($row2['MunkaFajta']=='Műszerész'){
        $muszereszmido=$muszereszmido+$row['Mennyiseg'];
      }
      echo "<td>".str_repeat("&nbsp;", $depth * 5).$row['Megnevezes']."</td>";
      echo "<td>".$row["ME"]."</td>";
      echo "<td>".$row["Mennyiseg"]."</td>";
      echo "<td>".$row2["Oraber"]."</td>";
      if ($row['Mennyiseg']!=NULL) {
        $sorar=$row['Mennyiseg']*$row2['Oraber'];
        echo "<td>".$sorar."</td>";
        $osszegar=$osszegar+$sorar;
      }
      else {
        echo "<td></td>";
      }
      echo "</tr>";
    }
  }

  echo  "<tr>";
  echo  "<td></td>";
  echo  "<td colspan='3' align='right'>Összegzett ár:</td>";
  echo  "<td align='left'>$osszegar</td>";
  echo  "</tr>";
  return $osszegar;
}

function show_children2($parentID, $depth=1){
  require 'includes/dbh.inc.php';
  $children = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE parent_id=$parentID");
  /*$munkadij = mysqli_query($conn,"SELECT Oraber FROM munkadij
                INNER JOIN egyebkoltseg
                ON munkadij.MunkaFajta = egyebkoltseg.Megnevezes
                WHERE egyebkoltseg.Id ='$parentID'");
  $row2=mysqli_fetch_array($munkadij);*/

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['Id'];?>
    <tr>
    <?php if ($row['Mennyiseg']==NULL) {

      echo "<td><b>".str_repeat("&nbsp;", $depth * 5).$row['Megnevezes']."</b></td>";
      echo "<td></td><td></td><td></td><td></td>";?>

      <?php echo "</tr>";
      $arresz = show_children($row['Id'], $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT Oraber FROM munkadij
                    INNER JOIN egyebkoltseg
                    ON munkadij.Id = egyebkoltseg.munkadij_id
                    WHERE egyebkoltseg.Id ='$sorid'");
      $row2=mysqli_fetch_array($munkadij);
      echo "<td>".str_repeat("&nbsp;", $depth * 5).$row['Megnevezes']."</td>";
      echo "<td>".$row["ME"]."</td>";
      echo "<td>".$row["Mennyiseg"]."</td>";
      echo "<td>".$row2["Oraber"]."</td>";
      if ($row['Mennyiseg']!=NULL) {
        $sorar=$row['Mennyiseg']*$row2['Oraber'];
        echo "<td>".$sorar."</td>";
        $osszegar=$osszegar+$sorar;
      }
      else {
        echo "<td></td>";
      }
      echo "</tr>";
    }
  }

  echo  "<tr>";
  echo  "<td></td>";
  echo  "<td colspan='3' align='right'>Összegzett ár:</td>";
  echo  "<td align='left'>$osszegar</td>";
  echo  "</tr>";

  return $osszegar;
}
?>

<?php
    require "footer.php";
?>
