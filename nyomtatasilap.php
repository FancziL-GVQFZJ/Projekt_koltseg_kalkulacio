<?php
    $thisPage='Kalkulacioslap';
    $thisPage1='Nyomtatas';
    require "kalkulacioslapheader.php";
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
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId'])) { ?>

          <?php
          $pid = $_SESSION['projektId'];
          $checkRecord1 = mysqli_query($conn,"SELECT * FROM pa_kapcsolat WHERE projekt_id = '$pid'");
          $totalrows1 = mysqli_num_rows($checkRecord1);
          $checkRecord5 = mysqli_query($conn,"SELECT * FROM anyagkoltseg WHERE projekt_id = '$pid'");
          $totalrows5 = mysqli_num_rows($checkRecord5);
          $checkRecord2 = mysqli_query($conn,"SELECT * FROM munkadijkoltseg WHERE projekt_id = '$pid'");
          $totalrows2 = mysqli_num_rows($checkRecord2);
          $checkRecord3 = mysqli_query($conn,"SELECT * FROM egyebkoltseg WHERE projekt_id = '$pid'");
          $totalrows3 = mysqli_num_rows($checkRecord3);
          $checkRecord4 = mysqli_query($conn,"SELECT * FROM muszakitartalom WHERE projekt_id = '$pid'");
          $totalrows4 = mysqli_num_rows($checkRecord4);
          ?>

          <p>Nyomtatási adatok</p>
          <form action='includes/nyomtatas.inc.php' method='post'><br>
          Tárgy: <input type='text' name='name'><br>
          <?php if ($totalrows1 > 0) { ?>
            <input type='checkbox' name='Anyaglista' value='1'>Anyaglista<br>
          <?php } ?>
          <?php if ($totalrows1 > 0) { ?>
            <input type='checkbox' name='Anyagkoltseg' value='2'>Anyagköltség<br>
          <?php } ?>
          <?php if ($totalrows2 > 0) { ?>
            <input type='checkbox' name='Munkadíj' value='3'>Munkadíj költség<br>
          <?php } ?>
          <?php if ($totalrows3 > 0) { ?>
            <input type='checkbox' name='Egyéb' value='4'>Egyéb költség<br>
          <?php } ?>
          <?php if ($totalrows4 > 0) { ?>
            <input type='checkbox' name='Műszaki' value='5'>Műszaki tartalom<br>
          <?php } ?>
          <input type='submit' value='Nyomtatás'  onclick="$('form').attr('target', '_blank');">
          </form>

          <?php

        }
        else {
          echo '<p>Jelenleg ki van jelentkezve!</p>';
        }
        ?>
      </div>
    </main>
  </div>
</div>


<?php
    require "footer.php";
?>
