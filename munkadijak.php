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
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) {
          echo '<nav class="topnav">
            <ul>
              <li><a href="index.php">Projektek</a></li>
              <li><a href="jogosultsagok.php">Jogosultságok</a></li>
              <li><a style="background-color: #ddd;" href="#">Munkadíjak</a></li>
              <li><a href="naplo.php">Naplo</a></li>
            </ul>
          </nav>';

          echo "<p>Új adat felvétele</p>
          <form action='includes/addtomunkadij.inc.php' method='post'>
          Megnevezés: <input type='text' name='name'>
          Órabér: <input type='text' name='oraber'>
          <input type='submit'>
          </form>";

          echo "<table id='Munkadijak'>";
          echo "<tr class='fejlec'>";
          echo "<th>Id</th><th>Munkafajta</th><th>Órabér</th>";

          $pid = $_SESSION['projektId'];
          $dij=mysqli_query($conn,"SELECT * FROM munkadij");

          while ($row=mysqli_fetch_array($dij))
          {?>

            <tr id="<?php echo $row['Id']; ?>">
            <?php echo "<td>".$row['Id']."</td>";
            echo "<td>".$row['MunkaFajta']."</td>";
            echo "<td>".$row["Oraber"]."</td>";
            $sorid=$row['Id'];?>
            <td id='del'><span class='deletemdij' data-id='<?= $sorid; ?>'>Törlés</span></td>
            </tr>
            <?php
          }
          print "</table>";
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
    $('#Munkadijak').Tabledit({
      deleteButton: false,
      editButton: false,
      columns: {
      identifier: [0, 'Id'],
      editable: [[1, 'MunkaFajta'],[2, 'Oraber']]
    },
    hideIdentifier: true,
    url: 'includes/mdijlive_edit.inc.php',
    onAlways: function() {location.reload()}
  });
  //Location.reload();
});
</script>

<?php
  require "footer.php";
?>
