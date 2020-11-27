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
        if (isset($_SESSION['userId'])) { ?>

          <!-- keresőmező -->
          <p class='szoveg'>Keresés a naplóban:</p>
          <input type="text" id="myInput" onkeyup="myFunction2()" placeholder="Keresés..">

          <!-- táblázat kiírása -->
          <table class='table-style' id='Naplo'>
          <tr class='fejlec'>
          <th>Dátum</th><th>Adat</th>
          <?php $sor=mysqli_query($conn, "SELECT * FROM naplo ORDER BY naplo_datum desc");
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
