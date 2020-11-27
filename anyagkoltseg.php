<?php
  $thisPage='Kalkulacioslap';
  $thisPage1='Anyag';
  require "kalkulacioslapheader.php";
  session_start();
?>

<div id="container">
  <div id="main">
    <main>
      <div>
        <?php
        require 'includes/kapcsolat.inc.php';
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) { ?>

          <!-- új adat felvétele -->
          <div class='felvetel'>
            <div class='felvetelin'>
              <p><u>Új adat felvétele:</u></p>
              <form action='includes/databaseinsert/addtoanyagkoltseg.inc.php' method='post'>
              Megnevezés: <input type='text' name='name' id='megnevezesid'><br><br><br>
              <input class='button' type='submit' value='Az adat felvétele' id='felvetelid' disabled>
            </form>
          </div>
          </div>

          <!-- anyagköltség táblázat -->
          <div align='center' class='table-style'>
            <table class='table-style' id='Anyagkoltseg'>
            <tr class='fejlec'>
            <th>Id</th><th>Anyagi megnevezés</th><th>Mértékegység</th><th>Mennyiség</th><th>Egységár</th><th>Összeg</th>
            <?php
            $pid = $_SESSION['projektId'];
            $sor=mysqli_query($conn, "SELECT * FROM sap_anyaglista
                  INNER JOIN pa_kapcsolat
                    ON sap_anyaglista.sap_anyaglista_id = pa_kapcsolat.alkatresz_id
                  INNER JOIN projekt
                    ON pa_kapcsolat.projekt_id = projekt.projekt_id
                    WHERE projekt.projekt_id = '$pid'
                    ORDER BY sap_anyaglista.sap_anyaglista_id");
            while ($row=mysqli_fetch_array($sor))
            {
              $sorar=$row['sap_anyaglista_egysegar']*$row['pa_dbszam'];
              $anyaglistaar=$anyaglistaar+$sorar;
            }

            $query2="SELECT * FROM anyagkoltseg WHERE projekt_id = '$pid'";
            $sor2=mysqli_query($conn,$query2);

            while ($row=mysqli_fetch_array($sor2))
            {?>

              <tr id="<?php echo $row['anyagkoltseg_id']; ?>">
              <?php echo "<td>".$row['anyagkoltseg_id']."</td>";
              echo "<td>".$row['anyagkoltseg_megnevezes']."</td>";
              echo "<td>".$row["anyagkoltseg_mertekegyseg"]."</td>";
              echo "<td>".$row["anyagkoltseg_mennyiseg"]."</td>";
              echo "<td>".$row["anyagkoltseg_egysegar"]."</td>";
              $sorar=$row["anyagkoltseg_mennyiseg"]*$row["anyagkoltseg_egysegar"];
              echo "<td>".$sorar."</td>";
              $anyagkoltseg=$anyagkoltseg+$sorar;
              $sorid=$row['anyagkoltseg_id'];?>
              <td id='del'><span class='deleteak' data-id='<?= $sorid; ?>'>Törlés</span></td>
              </tr>
            <?php }
            $anyagkoltseg=$anyagkoltseg+$anyaglistaar; ?>
            <tr>
            <td>0</td>
            <td>villamos szerelési anyag</td>
            <td>db</td>
            <td>1</td>
            <td align='left'><?php echo $anyaglistaar; ?></td>
            <td align='left'><?php echo $anyaglistaar; ?> Ft</td>
            </tr>
            <tr>
            <td></td>
            <td colspan='4' align='center'>Összesen:</td>
            <td align='left'><?php echo $anyagkoltseg; ?> Ft</td>
            </tr>
            </table>
          <div>
          <?php }
        else { ?>
          <p>Jelenleg ki van jelentkezve!</p>
        <?php } ?>
      </div>
    </main>
  </div>
</div>

<!-- tableedit script az adatok változtatásához-->
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
    url: 'includes/tableedit/aklive_edit.inc.php',
    onAlways: function() {location.reload()}
  });
});
</script>

<!-- felvétel mező gomja csak akkor elérhető ha van adat megadva -->
<script type="text/javascript">
$("#megnevezesid").keyup(function () {
       if ($(this).val()) {
         document.getElementById("felvetelid").disabled = false;
       }
       else {
         document.getElementById("felvetelid").disabled = true;
       }
    });
</script>

<?php
    require "footer.php";
?>
