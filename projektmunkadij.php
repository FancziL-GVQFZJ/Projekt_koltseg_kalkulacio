<?php
  $thisPage1='Munka';
  $thisPage='Kalkulacioslap';
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

          <div class='felvetel'>
            <div class='felvetelin'>
              <p><u>Új adat felvétele:</u></p>
              <form action='includes/databaseinsert/addtopmunkadij.inc.php' method='post'>
              Megnevezés: <input type='text' name='name' id='megnevezesid'><br>
              Órabér: <input type='text' name='oraber' id='oraberid'><br><br>
              <input class='button' type='submit' id='felvetelid' value='Az adat felvétele' onkeypress='return mask(this,event);'>
              </form>
            </div>
          </div>

          <table class='table-style' id='Munkadijak'>
          <tr class='fejlec'>
          <th>Id</th><th>Munkafajta</th><th>Órabér</th>

          <?php $pid = $_SESSION['projektId'];
          $dij=mysqli_query($conn,"SELECT * FROM projektmunkadij WHERE projekt_id='$pid'");

          while ($row=mysqli_fetch_array($dij))
          { ?>
            <tr id="<?php echo $row['projektmunkadij_id']; ?>">
            <?php echo "<td>".$row['projektmunkadij_id']."</td>";
            echo "<td>".$row['projektmunkadij_munkafajta']."</td>";
            echo "<td>".$row["projektmunkadij_oraber"]."</td>";
            $sorid=$row['projektmunkadij_id'];?>
            <td id='del'><span class='deletepmdij' data-id='<?= $sorid; ?>'>Törlés</span></td>
            </tr>
            <?php
          }
          print "</table>";
        }
        else {
          echo '<p>Jelenleg ki van jelentkezve!</p>';
        }
        ?>
      </div>
    </main>
  </div>
</div>

<!-- tableedit szkriptje -->
<script type="text/javascript" src="/Projekt_koltseg_kalkulacio/js/jquery.tabledit.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#Munkadijak').Tabledit({
      deleteButton: false,
      editButton: false,
      columns: {
      identifier: [0, 'Id'],
      editable: [[1, 'MunkaFajta'],[2, 'Oraber']]
    },
    hideIdentifier: true,
    url: 'includes/tableedit/pmdlive_edit.inc.php',
    onAlways: function() {location.reload()}
  });
});
</script>

<!-- adatbevitelt szabályozó szkriptek -->
<script type="text/javascript">
$(document).ready(function() {
    var $submit = $("input[type=submit]"),
        $inputs = $('input[type=text], input[type=text]');

    function checkEmpty() {
        return $inputs.filter(function() {
            return !$.trim(this.value);
        }).length === 0;
    }

    $inputs.on('blur', function() {
        $submit.prop("disabled", !checkEmpty());
    }).blur();
});
</script>

<?php
  require "footer.php";
?>
