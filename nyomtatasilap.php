<?php
  $thisPage='Kalkulacioslap';
  $thisPage1='Nyomtatas';
  require "kalkulacioslapheader.php";
  session_start();
?>

<div id="container">
  <div id="main">
    <main>
      <div class='lap1'>
        <?php
        require 'includes/kapcsolat.inc.php';
        require "PHPExcel/Classes/PHPExcel.php";
        require "PHPExcel/Classes/PHPExcel/Writer/Excel5.php";
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId'])) { ?>

          <!-- nyomtatás táblázat -->
          <p class="szoveg">Nyomtatási adatok:</p>
          <form action='includes/nyomtatas/nyomtatas.inc.php' method='post'><br>
          <!-- Tárgy: <input type='text' name='name'><br> -->
          <table class='table-style'>
          <tr class='fejlec'>
          <th></th><th>Nyomtatvány</th>
          <tr><td>1</td><td>
            <input type='checkbox' name='Anyaglista' value='1'> Anyaglista<br>
          </tr></td>
          <tr><td>2</td><td>
            <input type='checkbox' name='Anyagkoltseg' value='2'> Anyagköltség<br>
          </tr></td>
          <tr><td>3</td><td>
            <input type='checkbox' name='Munkadíj' value='3'> Munkadíj költség<br>
          </tr></td>
          <tr><td>4</td><td>
            <input type='checkbox' name='Egyéb' value='4'> Egyéb költség<br>
          </tr></td>
          <tr><td>5</td><td>
            <input type='checkbox' name='Műszaki' value='5'> Műszaki tartalom<br>
          </table>
          <input class="button" type='submit' value='Nyomtatás'  onclick="$('form').attr('target', '_blank');">
          </form>
          <br>
          <form action="includes/excel.inc.php" method="post">
            <input class="button" type="submit" name="excelexport" value="Anyaglista exportálása excelbe">
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
