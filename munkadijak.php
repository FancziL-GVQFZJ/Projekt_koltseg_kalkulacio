<?php
  $thisPage1='Munkadijak';
  $thisPage='Kezdooldal';
  require "kezdolapheader.php";
  session_start();
?>

<div id="container">
  <div id="main">
    <main>
      <div>
        <?php
        require 'includes/kapcsolat.inc.php';
        if (isset($_SESSION['userId'])) { ?>

          <!-- új adat felvétele -->
          <div class='felvetel'>
            <div class='felvetelin'>
              <p><u>Új adat felvétele:</u></p>
              <form action='includes/databaseinsert/addtomunkadij.inc.php' method='post'>
              Megnevezés: <input type='text' name='name' id='megnevezesid'><br>
              Órabér: <input type='text' name='oraber' id='oraberid' ><br><br>
              <input class='button' type='submit' id='felvetelid' value='Az adat felvétele'>
              </form>
            </div>
          </div>

          <!-- táblázat kiírása -->
          <table class='table-style' id='Munkadijak'>
          <tr class='fejlec'>
          <th>Id</th><th>Munkafajta</th><th>Órabér</th>

          <?php $pid = $_SESSION['projektId'];
          $dij=mysqli_query($conn,"SELECT * FROM munkadij");
          while ($row=mysqli_fetch_array($dij))
          {?>
            <tr id="<?php echo $row['munkadij_id']; ?>">
            <?php echo "<td>".$row['munkadij_id']."</td>";
            echo "<td>".$row['munkadij_fajta']."</td>";
            echo "<td>".$row["munkadij_oraber"]."</td>";
            $sorid=$row['munkadij_id'];
            if (($sorid == 1) || ($sorid == 2)) {
            } else { ?>
              <td id='del'><span class='deletemdij' data-id='<?= $sorid; ?>'>Törlés</span></td>
            <?php }
            echo "</tr>";
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

<!-- tableedit scriptje -->
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
    url: 'includes/tableedit/mdijlive_edit.inc.php',
    onAlways: function() {location.reload()}
  });
});
</script>

<!-- az adatbevitelt szabályozó scriptek -->
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
