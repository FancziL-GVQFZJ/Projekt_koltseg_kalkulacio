<?php
  $thisPage='Anyaglista';
  require "header.php";
  session_start();
?>
<style><?php require 'css/navbar.css';?></style>
<style><?php require 'css/table.css';?></style>

<div id="container">
  <div id="main">
    <main>
      <?php
        require 'includes/dbh.inc.php';
        //require 'includes/nyomtatas.inc.php';
        require "PHPExcel/Classes/PHPExcel.php";
        require "PHPExcel/Classes/PHPExcel/Writer/Excel5.php";
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) {

          echo '<nav class="topnav">
              <a href="Anyaglista.php">Villamos anyaglista</a>
              <a href="belsoanyaglista.php">Belső villamos anyaglista</a>
              <a style="background-color: #ddd;"href="#">Listázott anyagok</a>
              <a href="osszehasonlitas.php">Összehasonlítás</a>
          </nav>';

            $pid = $_SESSION['projektId'];
            $sql = "SELECT * FROM alkatresz
                  INNER JOIN pa_kapcsolat
                    ON alkatresz.id = pa_kapcsolat.alkatresz_id
                  INNER JOIN projekt
                    ON pa_kapcsolat.projekt_id = projekt.idProjekt
                    WHERE projekt.idProjekt = $pid
                    ORDER BY alkatresz.id";

            $sor=mysqli_query($conn, $sql);

            echo "<div align= \"center\" id=\"nyomtatas\">";
              echo "<table id='KosarTable'>";
              echo "<tr class='fejlec'>";
              echo "<th>id</th><th></th><th>Megnevezés</th><th>SAPSzám</th><th>Mérték egység</th><th>Egységár</th><th>Darabszám</th>";
              echo "<td><p>ÁR összesen</p></td>";
              $i=1;
              while ($row=mysqli_fetch_array($sor))
              {
                $sorid=$row['alkatresz_id'];
                $sorar=$row['Egysegar']*$row['DBszam'];
                $egeszar=$egeszar+$sorar;?>
                <tr id="<?php echo $row['alkatresz_id']; ?>">
                <?php echo "<td>".$row['alkatresz_id']."</td>";
                echo "<td>".$i."</td>";
                echo "<td>".$row['Megnevezes']."</td>";
                echo "<td>".$row['SAPSzam']."</td>";
                echo "<td>".$row['ME']."</td>";
                echo "<td>".$row['Egysegar']."</td>";
                echo "<td>".$row['DBszam']."</td>";
                echo "<td>".$sorar."</td>";?>
                <td id='del'><span class='delete' data-id='<?= $sorid; ?>'>Törlés</span></td>
                <?php echo "</tr>";
                $i++;
              }
              echo  "<tr>";
              echo  "<td></td>";
              echo  "<td colspan='6' align='right'>A teljes ár:</td>";
              echo  "<td align='left'>$egeszar</td>";
              echo  "</tr>";
              echo  "</table>";
            echo  "</div>";?>

            <form action="includes/excel.inc.php" method="post">
              <input type="submit" name="excelexport" value="Exportálás excelbe">
            </form>
            <?php
          }
        else {
          echo '<p>You are logged out!</p>';
        }
      ?>
    </main>
  </div>
</div>

<script type="text/javascript" src="/Projekt_koltseg_kalkulacio/js/jquery.tabledit.js"></script>
<script type="text/javascript">
$(document).ready(function(){
  $('#KosarTable').Tabledit({
    deleteButton: false,
    editButton: false,
    columns: {
    identifier: [0, 'alkatresz_id'],
    editable: [[6, 'DBszam']]
  },
  hideIdentifier: true,
  url: 'includes/live_edit.inc.php',
  onAlways: function() {location.reload()}
});
});
</script>

<?php
    require "footer.php";
?>
