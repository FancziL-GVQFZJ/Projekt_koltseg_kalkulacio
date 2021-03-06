<?php
  $thisPage='Anyaglista';
  $thisPage1='SAP';
  require "anyaglistaheader.php";
  session_start();
?>

<div id="container">
  <div id="main">
    <main>
      <?php
        require 'includes/kapcsolat.inc.php';
        if (isset($_SESSION['userId'])) { ?>

          <!-- keresőmező -->
          <p class="szoveg" >Keresés az SAP alkatrész listában</p>
          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Keresés..">

          <!-- táblázat kiírása -->
          <table class='table-style' id='AnyaglistaTable'>
          <tr class='fejlec'>
          <th>SAP anyagszám</th><th>Megnevezés</th><th>Mérték egység</th><th>Egységár</th>
          <?php $sor=mysqli_query($conn,"SELECT * FROM sap_anyaglista");
          while ($row=mysqli_fetch_array($sor))
          {
            $sorid=$row['sap_anyaglista_id'];
            echo "<tr>";
            echo "<td>".$row['sap_anyaglista_id']."</td>";
            echo "<td>".$row['sap_anyaglista_megnevezes']."</td>";
            echo "<td>".$row['sap_anyaglista_mertekegyseg']."</td>";
            echo "<td>".$row['sap_anyaglista_egysegar']."</td>";?>
            <td id='add'><span class='addtohelyi' data-id='<?= $sorid; ?>'>Helyibe</span></td>
            <td id='add'><span class='addtocart' data-id='<?= $sorid; ?>'>Kiválasztás</span></td>
            <?php echo "</tr>";
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
