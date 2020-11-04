<?php
    $thisPage='Kalkulacioslap';
    $thisPage1='Kalkulacio';
    require "kalkulacioslapheader.php";
    session_start();
?>
<style><?php include 'css/navbar.css';?></style>
<style><?php include 'css/table.css';?></style>

<div id="container">
  <div id="main">
    <main>
      <div>
        <?php
        require 'includes/kapcsolat.inc.php';
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId'])) { ?>

          <?php
          $pid = $_SESSION['projektId'];
          $sql = "SELECT * FROM helyi_anyaglista
                INNER JOIN pa_kapcsolat
                  ON helyi_anyaglista.helyi_anyaglista_id = pa_kapcsolat.alkatresz_id
                INNER JOIN projekt
                  ON pa_kapcsolat.projekt_id = projekt.projekt_id
                  WHERE projekt.projekt_id = '$pid'
                  ORDER BY helyi_anyaglista.helyi_anyaglista_id";
          $sor=mysqli_query($conn, $sql);

          echo "<div align= \"center\" id=\"nyomtatas\">";
            $checkRecord1 = mysqli_query($conn,"SELECT * FROM pa_kapcsolat WHERE projekt_id = '$pid'");
            $totalrows1 = mysqli_num_rows($checkRecord1);

            if ($totalrows1 > 0) {
              echo "<p style='font-size: large;'><b><u>Villamos anyaglista</u></b></p>";
              echo "<table border=1; style='border-collapse: collapse;' id='KosarTable'>";
              echo "<tr class='fejlec'>";
              echo "<th>id</th><th>Megnevezés</th><th>SAPSzám</th><th>Mérték egység</th><th>Egységár</th><th>Darabszám</th>";
              echo "<td><p>ÁR összesen</p></td>";
              $i=1;
              while ($row=mysqli_fetch_array($sor))
              {
                $sorid=$row['alkatresz_id'];
                echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td>".$row['helyi_anyaglista_megnevezes']."</td>";
                echo "<td>".$row['helyi_anyaglista_sapszam']."</td>";
                echo "<td>".$row['helyi_anyaglista_mertekegyseg']."</td>";
                echo "<td>".$row['helyi_anyaglista_egysegar']."</td>";
                echo "<td>".$row['pa_dbszam']."</td>";
                $sorar=$row['helyi_anyaglista_egysegar']*$row['pa_dbszam'];
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

          $checkRecord5 = mysqli_query($conn,"SELECT * FROM anyagkoltseg WHERE projekt_id = '$pid'");
          $totalrows5 = mysqli_num_rows($checkRecord5);

          if ($totalrows5 >0) {
            echo "<p style='font-size: large;'><b><u>Anyagköltség</u></b></p>";
            echo "<table border=1; style='border-collapse: collapse;' id='Anyagkoltseg'>";
            echo "<tr class='fejlec'>";

            echo "<th>Anyagi megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Egységár</th><th>Összeg</th>";
            $pid = $_SESSION['projektId'];

            $query = "SELECT * FROM helyi_anyaglista
                  INNER JOIN pa_kapcsolat
                    ON helyi_anyaglista.helyi_anyaglista_id = pa_kapcsolat.alkatresz_id
                  INNER JOIN projekt
                    ON pa_kapcsolat.projekt_id = projekt.projekt_id
                    WHERE projekt.projekt_id = $pid
                    ORDER BY helyi_anyaglista.helyi_anyaglista_id";

            $sor=mysqli_query($conn, $query);
            while ($row=mysqli_fetch_array($sor))
            {
              $sorar=$row['helyi_anyaglista_egysegar']*$row['pa_dbszam'];
              $anyaglistaar=$anyaglistaar+$sorar;
            }
            $query2="SELECT * FROM anyagkoltseg WHERE projekt_id = '$pid'";
            $sor2=mysqli_query($conn,$query2);

            while ($row=mysqli_fetch_array($sor2))
            {
              echo "<tr>";
              echo "<td>".$row['anyagkoltseg_megnevezes']."</td>";
              echo "<td>".$row["anyagkoltseg_mertekegyseg"]."</td>";
              echo "<td>".$row["anyagkoltseg_mennyiseg"]."</td>";
              echo "<td>".$row["anyagkoltseg_egysegar"]."</td>";
              $sorar=$row["anyagkoltseg_mennyiseg"]*$row["anyagkoltseg_egysegar"];
              echo "<td>".$sorar."</td>";
              $teljesar=$teljesar+$sorar;
              echo "</tr>";
            }
            echo  "<tr>";
            echo  "<td>villamos szerelési anyag</td>";
            echo  "<td>db</td>";
            echo  "<td>1</td>";
            echo  "<td align='left'>$anyaglistaar Ft</td>";
            echo  "<td align='left'>$anyaglistaar Ft</td>";
            echo  "</tr>";
            echo  "<tr>";
            echo  "<td colspan='4' align='center'>Összesen:</td>";
            $teljesar=$teljesar+$anyaglistaar;
            echo  "<td align='left'>$teljesar Ft</td>";
            echo  "</tr>";
            print "</table>";
          }

          $checkRecord2 = mysqli_query($conn,"SELECT * FROM munkadijkoltseg WHERE projekt_id = '$pid'");
          $totalrows2 = mysqli_num_rows($checkRecord2);

          if ($totalrows2 > 0) {
            $mernokmido=0;
            $muszereszmido=0;

            echo "<p style='font-size: large;'><b><u>Munkadíj költség</u></b></p>";
            echo "<table border=1; style='border-collapse: collapse;' id='Munkadijkalkulacio'>";
            echo "<tr class='fejlec'>";
            echo "<th>Megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Órabér</th><th>Ár:</th>";
            $query="SELECT * FROM munkadijkoltseg WHERE parent_id IS NULL AND projekt_id = '$pid'";
            $parents=mysqli_query($conn,$query);
            $totalrows = mysqli_num_rows($parents);
            if ($totalrows > 1) {
              $i=1;
            }else {
              $i=0;
            }

            while ($row=mysqli_fetch_array($parents))
            {?>
              <tr>
              <?php
              echo "<td><b>".$row['munkadijkoltseg_megnevezes']."</b></td>";
              echo "<td>".$row["munkadijkoltseg_mertekegyseg"]."</td>";
              echo "<td>".$row["munkadijkoltseg_mennyiseg"]."</td>";
              echo "<td></td><td></td>";
              $sorid=$row['munkadiijkoltseg_id'];?>
              </tr>
              <?php
              $arresz = show_children($row['munkadiijkoltseg_id'], $i);
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
          }


          $checkRecord3 = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE projekt_id = '$pid'");
          $totalrows3 = mysqli_num_rows($checkRecord3);

          if ($totalrows3 > 0) {

            echo "<p style='font-size: large;'><b><u>Egyéb költség</u></b></p>";
            echo "<table border=1; style='border-collapse: collapse;' id='Egyebkoltsegkalkulacio'>";
            echo "<tr class='fejlec'>";
            echo "<th>Megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Órabér</th><th>Ár:</th>";
            $query="SELECT * FROM egyebkoltseg WHERE parent_id IS NULL AND projekt_id = '$pid'";
            $parents=mysqli_query($conn,$query);

            while ($row=mysqli_fetch_array($parents))
            {
              echo "<tr>";
              echo "<td><b>".$row['egyebkoltseg_megnevezes']."</b></td>";
              echo "<td>".$row["egyebkoltseg_mertekegyseg"]."</td>";
              echo "<td>".$row["egyebkoltseg_mennyiseg"]."</td>";
              echo "<td></td><td></td>";
              $sorid=$row['egyebkoltseg_id'];
              echo "</tr>";
              $arresz = show_children2($row['egyebkoltseg_id']);
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
            $leiras=$row['muszakitartalom_tartalom'];
            echo "<p style='font-size: large;'><b><u>Műszaki tartalom</u></b></p>";
             echo '<div style="border:solid 1px #000"; id="mszt";>';
              echo htmlspecialchars_decode($leiras);
             echo "</div>";
          }

         echo "</div>";
        }
        else {
          echo '<p>Jelenleg ki van jelentkezve!</p>';
        }
        ?>

      </div>
    </main>
  </div>
</div>
<?php

function show_children($parentID, $i, $depth=1){
  require 'includes/kapcsolat.inc.php';
  global $mernokmido,$muszereszmido;
  $pid = $_SESSION['projektId'];

  $children = mysqli_query($conn,"SELECT * FROM munkadijkoltseg WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['munkadiijkoltseg_id'];?>
    <tr>
    <?php if ($row['munkadijkoltseg_mennyiseg']==NULL) {
      echo "<td><b>".str_repeat("&nbsp;", $depth * 5).$row['munkadijkoltseg_megnevezes']."</b></td>";
      echo "<td></td><td></td><td></td><td></td>";
      echo "</tr>";
      $totalrows = mysqli_num_rows($children);
      if ($totalrows > 1) {
        $i=1;
      }else {
        $i=0;
      }
      $arresz = show_children($row['munkadiijkoltseg_id'], $i, $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM projektmunkadij
                    INNER JOIN munkadijkoltseg
                    ON projektmunkadij.munkadij_id = munkadijkoltseg.munkadij_id
                    WHERE munkadijkoltseg.munkadiijkoltseg_id ='$sorid' AND projektmunkadij.projekt_id='$pid'");
      $row2=mysqli_fetch_array($munkadij);
      if ($row2['munkadij_id']==1) {
        $mernokmido=$mernokmido+$row['munkadijkoltseg_mennyiseg'];
      }
      elseif ($row2['munkadij_id']==2){
        $muszereszmido=$muszereszmido+$row['munkadijkoltseg_mennyiseg'];
      }
      echo "<td>".str_repeat("&nbsp;", $depth * 5).$row['munkadijkoltseg_megnevezes']."</td>";
      echo "<td>".$row["munkadijkoltseg_mertekegyseg"]."</td>";
      echo "<td>".$row["munkadijkoltseg_mennyiseg"]."</td>";
      echo "<td>".$row2["projektmunkadij_oraber"]."</td>";
      if ($row['munkadijkoltseg_mennyiseg']!=NULL) {
        $sorar=$row['munkadijkoltseg_mennyiseg']*$row2['projektmunkadij_oraber'];
        echo "<td>".$sorar."</td>";
        $osszegar=$osszegar+$sorar;
      }
      else {
        echo "<td></td>";
      }
      echo "</tr>";
    }
  }
  if ($i == 1) {
    echo  "<tr>";
    echo  "<td></td>";
    echo  "<td colspan='3' align='right'>Összegzett ár:</td>";
    echo  "<td align='left'>$osszegar</td>";
    echo  "</tr>";
  }
  return $osszegar;
}

function show_children2($parentID, $depth=1){
  require 'includes/kapcsolat.inc.php';
  $children = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE parent_id=$parentID");

  while ($row = mysqli_fetch_array($children)){
    $sorid=$row['egyebkoltseg_id'];?>
    <tr>
    <?php if ($row['egyebkoltseg_mennyiseg']==NULL) {

      echo "<td><b>".str_repeat("&nbsp;", $depth * 5).$row['egyebkoltseg_megnevezes']."</b></td>";
      echo "<td></td><td></td><td></td><td></td>";?>

      <?php echo "</tr>";
      $arresz = show_children($row['egyebkoltseg_id'], $depth+1);
      $osszegar=$osszegar+$arresz;
    }
    else {
      $munkadij = mysqli_query($conn,"SELECT * FROM munkadij
                    INNER JOIN egyebkoltseg
                    ON munkadij.munkadij_id = egyebkoltseg.munkadij_id
                    WHERE egyebkoltseg.egyebkoltseg_id ='$sorid'");
      $row2=mysqli_fetch_array($munkadij);
      echo "<td>".str_repeat("&nbsp;", $depth * 5).$row['egyebkoltseg_megnevezes']."</td>";
      echo "<td>".$row["egyebkoltseg_mertekegyseg"]."</td>";
      echo "<td>".$row["egyebkoltseg_mennyiseg"]."</td>";
      echo "<td>".$row2["munkadij_oraber"]."</td>";
      if ($row['egyebkoltseg_mennyiseg']!=NULL) {
        $sorar=$row['egyebkoltseg_mennyiseg']*$row2['munkadij_oraber'];
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
