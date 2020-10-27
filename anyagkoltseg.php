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
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) { ?>
          <nav class="topnav">
            <ul>
              <li><a style="background-color: #ddd;" href="#">Anyagköltség</a></li>
              <li><a href="munkadijkoltseg.php">Munkadíj költség</a></li>
              <li><a href="egyebkoltseg.php">Egyéb költség</a></li>
              <li><a href="muszakitartalom.php">Műszaki tartalom</a></li>
              <li><a href="kalkulacioslap.php">Kalkulációs Lap</a></li>
              <li><a href="nyomtatasilap.php">Nyomtatási Lap</a></li>
            </ul>
          </nav>

          <p>Új adat felvétele</p>
          <form action='includes/addtoanyagkoltseg.inc.php' method='post'>
          Megnevezés: <input type='text' name='name'>
          <input type='submit' value='Felvétel'>
          </form>

          <div align= "center" id="nyomtatas">
            <table id='Anyagkoltseg'>
            <tr class='fejlec'>
            <th>Id</th><th>Anyagi megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Egységár</th><th>Összeg</th>

            <?php
            $pid = $_SESSION['projektId'];

            $query = "SELECT * FROM alkatresz
                  INNER JOIN pa_kapcsolat
                    ON alkatresz.id = pa_kapcsolat.alkatresz_id
                  INNER JOIN projekt
                    ON pa_kapcsolat.projekt_id = projekt.idProjekt
                    WHERE projekt.idProjekt = '$pid'
                    ORDER BY alkatresz.id";

            $sor=mysqli_query($conn, $query);
            while ($row=mysqli_fetch_array($sor))
            {
              $sorar=$row['Egysegar']*$row['DBszam'];
              $anyaglistaar=$anyaglistaar+$sorar;
            }

            $query2="SELECT * FROM anyagkoltseg WHERE projekt_id = '$pid'";
            $sor2=mysqli_query($conn,$query2);

            while ($row=mysqli_fetch_array($sor2))
            {?>

              <tr id="<?php echo $row['Id']; ?>">
              <?php echo "<td>".$row['Id']."</td>";
              echo "<td>".$row['Megnevezes']."</td>";
              echo "<td>".$row["ME"]."</td>";
              echo "<td>".$row["Mennyiseg"]."</td>";
              echo "<td>".$row["Egysegar"]."</td>";
              $sorar=$row["Mennyiseg"]*$row["Egysegar"];
              echo "<td>".$sorar."</td>";
              $teljesar=$teljesar+$sorar;
              $sorid=$row['Id'];?>
              <td id='del'><span class='deleteak' data-id='<?= $sorid; ?>'>Törlés</span></td>
              </tr>
              <?php

            }
            echo  "<tr>";
            echo  "<td>0</td>";
            echo  "<td>villamos szerelési anyag</td>";
            echo  "<td>db</td>";
            echo  "<td>1</td>";
            echo  "<td align='left'>$anyaglistaar Ft</td>";
            echo  "<td align='left'>$anyaglistaar Ft</td>";
            echo  "</tr>";
            echo  "<tr>";
            echo  "<td></td>";
            echo  "<td colspan='4' align='center'>Összesen:</td>";
            $teljesar=$teljesar+$anyaglistaar;
            echo  "<td align='left'>$teljesar Ft</td>";
            echo  "</tr>";
            print "</table>";
          echo  "</div>";
          }
        else {
          echo '<p>You are logged out!</p>';
        }
        ?>
      </div>
    </main>
  </div>
</div>

<script type="text/javascript" src="/Projekt_koltseg_kalkulacio/js/jquery.tabledit.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#Anyagkoltseg').Tabledit({
      deleteButton: false,
      editButton: false,
      columns: {
      identifier: [0, 'Id'],
      editable: [[1, 'Megnevezes'],[2, 'ME'],[3, 'Mennyiseg'],[4, 'Egysegar']]
    },
    hideIdentifier: true,
    url: 'includes/aklive_edit.inc.php',
    onAlways: function() {location.reload()}
  });
  //Location.reload();
});
</script>


<?php
    require "footer.php";
?>
