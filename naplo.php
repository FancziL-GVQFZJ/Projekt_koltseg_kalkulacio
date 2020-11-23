<?php
  $thisPage1='Naplo';
  $thisPage='Kezdooldal';
  require "kezdolapheader.php";
  session_start();
?>

<div id="container">
  <div id="main">
    <main>
      <?php
      require 'includes/kapcsolat.inc.php';
        if (isset($_SESSION['userId'])) {

          echo "<p class='szoveg'>Keresés a naplóban:</p>";
          echo '<input type="text" id="myInput" onkeyup="myFunction2()" placeholder="Keresés..">';

          $sor=mysqli_query($conn, "SELECT * FROM naplo ORDER BY naplo_datum desc");

          echo "<table class='table-style' id='Naplo'>";
          echo "<tr class='fejlec'>";
          echo "<th>Dátum</th><th>Adat</th>";
          while ($row=mysqli_fetch_array($sor))
          {
            $sorid=$row['id'];
            echo "<tr>";
            echo "<td>".$row['naplo_datum']."</td>";
            echo "<td>".$row['naplo_esemeny']."</td>";
            echo "</tr>";
          }
          echo "</table>";
        }
        else {
          echo '<p>Jelenleg ki van jelentkezve!</p>';
        }
      ?>
    </main>
  </div>
</div>

<?php
    require "footer.php";
?>
