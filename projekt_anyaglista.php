<?php
  $thisPage='Anyaglista';
  $thisPage1='Projekt';
  require "anyaglistaheader.php";
  session_start();
?>

<div id="container">
  <div id="main">
    <main>
      <?php
        require 'includes/kapcsolat.inc.php';
        require "PHPExcel/Classes/PHPExcel.php";
        require "PHPExcel/Classes/PHPExcel/Writer/Excel5.php";
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) { ?>

            <div align='center'>
              <!-- táblázat exportálása excelbe -->
              <form align="left" action="includes/excel.inc.php" method="post">
                <input class="button" type="submit" name="excelexport" value="A táblázat exportálása Excelbe">
              </form>
              <!-- listázott anyagok táblázat -->
              <table class='table-style' id='KosarTable'>
              <tr class='fejlec'>
              <th>id</th><th></th><th>Megnevezés</th><th>SAPSzám</th><th>Mérték egység</th><th>Egységár</th><th>Darabszám</th><th>Összeg</th>
              <?php
              $pid = $_SESSION['projektId'];
              $sor=mysqli_query($conn, "SELECT * FROM sap_anyaglista
                      INNER JOIN pa_kapcsolat
                        ON sap_anyaglista.sap_anyaglista_id = pa_kapcsolat.alkatresz_id
                      INNER JOIN projekt
                        ON pa_kapcsolat.projekt_id = projekt.projekt_id
                        WHERE projekt.projekt_id = $pid
                        ORDER BY sap_anyaglista.sap_anyaglista_id");
              $i=1;
              while ($row=mysqli_fetch_array($sor))
              {
                $sorid=$row['sap_anyaglista_id'];
                $sorar=$row['sap_anyaglista_egysegar']*$row['pa_dbszam'];
                $teljesar=$teljesar+$sorar;?>
                <tr id="<?php echo $row['sap_anyaglista_id']; ?>">
                <?php echo "<td>".$row['sap_anyaglista_id']."</td>";
                echo "<td>".$i."</td>";
                echo "<td>".$row['sap_anyaglista_megnevezes']."</td>";
                echo "<td>".$row['sap_anyaglista_id']."</td>";
                echo "<td>".$row['sap_anyaglista_mertekegyseg']."</td>";
                echo "<td>".$row['sap_anyaglista_egysegar']." Ft</td>";
                echo "<td>".$row['pa_dbszam']."</td>";
                echo "<td>".$sorar." Ft</td>";?>
                <td id='del'><span class='delete' data-id='<?= $sorid; ?>'>Törlés</span></td>
                <?php echo "</tr>";
                $i++;
              } ?>
              <tr>
              <td></td>
              <td colspan='6' align='center'>Összesen:</td>
              <td align='left'><?php echo $teljesar; ?> Ft</td>
              </tr>
              </table>
            </div>
          <?php }
        else { ?>
          <p>Jelenleg ki van jelentkezve!</p>
        <?php } ?>
    </main>
  </div>
</div>

<!-- tabledit script a mennyiségek változtatásához  -->
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
