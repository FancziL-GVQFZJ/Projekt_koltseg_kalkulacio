<?php
  $thisPage1='Munkadijak';
  $thisPage='Kezdooldal';
  require "kezdolapheader.php";
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
        if (isset($_SESSION['userId'])) {

          echo "<p>Új adat felvétele</p>
          <form action='includes/databaseinsert/addtomunkadij.inc.php' method='post'>
          Megnevezés: <input type='text' name='name'>
          Órabér: <input type='text' name='oraber'>
          <input type='submit'>
          </form>";

          echo "<table class='table-style' id='Munkadijak'>";
          echo "<tr class='fejlec'>";
          echo "<th>Id</th><th>Munkafajta</th><th>Órabér</th>";

          $pid = $_SESSION['projektId'];
          $dij=mysqli_query($conn,"SELECT * FROM munkadij");

          while ($row=mysqli_fetch_array($dij))
          {?>

            <tr id="<?php echo $row['munkadij_id']; ?>">
            <?php echo "<td>".$row['munkadij_id']."</td>";
            echo "<td>".$row['munkadij_fajta']."</td>";
            echo "<td>".$row["munkadij_oraber"]."</td>";
            $sorid=$row['munkadij_id'];?>
            <td id='del'><span class='deletemdij' data-id='<?= $sorid; ?>'>Törlés</span></td>
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
  //Location.reload();
});
</script>

<?php
  require "footer.php";
?>
