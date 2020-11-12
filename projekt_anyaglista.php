<?php
  $thisPage='Anyaglista';
  $thisPage1='Projekt';
  require "anyaglistaheader.php";
  session_start();
?>
<style><?php require 'css/navbar.css';?></style>
<style><?php require 'css/table.css';?></style>

<div id="container">
  <div id="main">
    <main>
      <?php
        require 'includes/kapcsolat.inc.php';
        require "PHPExcel/Classes/PHPExcel.php";
        require "PHPExcel/Classes/PHPExcel/Writer/Excel5.php";
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) {

            $pid = $_SESSION['projektId'];
            $sor=mysqli_query($conn, "SELECT * FROM helyi_anyaglista
                    INNER JOIN pa_kapcsolat
                      ON helyi_anyaglista.helyi_anyaglista_id = pa_kapcsolat.alkatresz_id
                    INNER JOIN projekt
                      ON pa_kapcsolat.projekt_id = projekt.projekt_id
                      WHERE projekt.projekt_id = $pid
                      ORDER BY helyi_anyaglista.helyi_anyaglista_id");

            echo "<div align= \"center\" id=\"nyomtatas\">";
              ?>
              <form align="left" action="includes/excel.inc.php" method="post">
                <input type="submit" name="excelexport" value="Az adatok exportálása Excelbe">
              </form>
              <?php
              echo "<table id='KosarTable'>";
              echo "<tr class='fejlec'>";
              echo "<th>id</th><th></th><th>Megnevezés</th><th>SAPSzám</th><th>Mérték egység</th><th>Egységár</th><th>Darabszám</th>";
              echo "<td><p>ÁR összesen</p></td>";
              $i=1;
              while ($row=mysqli_fetch_array($sor))
              {
                $sorid=$row['helyi_anyaglista_id'];
                $sorar=$row['helyi_anyaglista_egysegar']*$row['pa_dbszam'];
                $teljesar=$teljesar+$sorar;?>
                <tr id="<?php echo $row['helyi_anyaglista_id']; ?>">
                <?php echo "<td>".$row['helyi_anyaglista_id']."</td>";
                echo "<td>".$i."</td>";
                echo "<td>".$row['helyi_anyaglista_megnevezes']."</td>";
                echo "<td>".$row['helyi_anyaglista_sapszam']."</td>";
                echo "<td>".$row['helyi_anyaglista_mertekegyseg']."</td>";
                echo "<td>".$row['helyi_anyaglista_egysegar']."</td>";
                echo "<td>".$row['pa_dbszam']."</td>";
                echo "<td>".$sorar." Ft</td>";?>
                <td id='del'><span class='delete' data-id='<?= $sorid; ?>'>Törlés</span></td>
                <?php echo "</tr>";
                $i++;
              }
              echo  "<tr>";
              echo  "<td></td>";
              echo  "<td colspan='6' align='right'>A teljes ár:</td>";
              echo  "<td align='left'>".$teljesar." Ft</td>";
              echo  "</tr>";
              echo  "</table>";
            echo  "</div>";
          }
        else {
          echo '<p>Jelenleg ki van jelentkezve!</p>';
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
    editable: [[6, 'pa_dbszam']]
  },
  hideIdentifier: true,
  url: 'includes/tableedit/live_edit.inc.php',
  onAlways: function() {location.reload()}
});
});
</script>

<?php
    require "footer.php";
?>
