<?php
  $thisPage1='Jogosultsagok';
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

          echo "<div class='kezdolap'>";
            echo "<div class='lap'>";
              echo '<p class="szoveg">Projektjeid:</p>';

              $fid = $_SESSION['userId'];
              $sor=mysqli_query($conn, "SELECT * FROM projekt
                    INNER JOIN pf_kapcsolat
                      ON projekt.projekt_id = pf_kapcsolat.projekt_id
                    INNER JOIN felhasznalo
                      ON pf_kapcsolat.felhasznalo_id = felhasznalo.felhasznalo_id
                      WHERE felhasznalo.felhasznalo_id = $fid
                      ORDER BY projekt.projekt_id DESC");

              echo "<table class='table-style'>";
              echo "<th></th><th>Projekt neve</th>";
              echo "<tr>";
              $i=1;
              while ($row=mysqli_fetch_array($sor))
              {
                $sorid=$row['projekt_id'];
                $pnev=$row['projekt_nev'];
                echo "<tr>";
                echo "<td>".$i."</td>";
                echo "<td>".$row['projekt_nev']."</td>";
                ?>
                <td style="text-align:center; width:100px;">
                  <form action="" method="post">
                  <input type="hidden" name="projektnev" value="<?php echo htmlentities($pnev); ?>">
                  <input type="hidden" name="projektid" value="<?php echo htmlentities($sorid); ?>">
                  <button class="button" type="submit" name="projektjog">Beállítás</button>
                  </form>
                </td>
                <?php
                echo "</tr>";
                $i++;
              }
              echo "</table>";
            echo "</div>";
            echo "<div class='lap'>";
              if(isset($_REQUEST['projektjog']))
              {
                echo "<p class='szoveg'><u>".$jpnev." beállítása:</u></p>";

                // jogosultság táblázat kiírása
                echo "<table class='table-style' id='table'>";
                echo "<tr>";
                echo "<th></th><th>Felhasználó</th><th>Projekt id</th><th>Jogosultság</th>";
                $jpnev = $_POST["projektnev"];
                $jpid = $_POST["projektid"];
                $sor2=mysqli_query($conn, "SELECT * FROM felhasznalo WHERE NOT felhasznalo_id ='$fid'");
                $i=1;
                while ($row2=mysqli_fetch_array($sor2))
                {
                  $fnev=$row2['felhasznalo_id'];
                  $jogosultsagok = mysqli_query($conn,"SELECT * FROM jogosultsag
                                INNER JOIN felhasznalo
                                ON jogosultsag.felhasznalo_id = felhasznalo.felhasznalo_id
                                WHERE jogosultsag.felhasznalo_id ='$fnev' AND jogosultsag.projekt_id ='$jpid'");
                  $row3=mysqli_fetch_array($jogosultsagok);
                  echo "<tr>";
                  echo "<td>".$i."</td>";
                  echo "<td>".$row2['felhasznalo_nev']."</td>";
                  echo "<td>".$jpid."</td>";
                  if ($row3['jogosultsag_iras']==1 && $row3['jogosultsag_olvasas']==1) {
                    echo "<td><select name='jogosultsagok' id='jogosultsagok'>
                      <option value='0' ></option>
                      <option value='1' selected>Írás</option>
                      <option value='2' >Olvasás</option>
                    </select>
                    </td>";
                  }
                  else if ($row3['jogosultsag_iras']==0 && $row3['jogosultsag_olvasas']==1) {
                    echo "<td><select name='jogosultsagok' id='jogosultsagok'>
                      <option value='0' ></option>
                      <option value='1' >Írás</option>
                      <option value='2' selected>Olvasás</option>
                    </select>
                    </td>";
                  }
                  else{
                    echo "<td><select name='jogosultsagok' id='jogosultsagok'>
                      <option value='0' selected></option>
                      <option value='1' >Írás</option>
                      <option value='2' >Olvasás</option>
                    </select>
                    </td>";
                  }
                  $i++;
                }
                echo "</table>";
              }
            echo "</div>";
          echo "</div>";
        }
        else {
          echo '<p>Jelenleg ki van jelentkezve!</p>';
        }
      ?>
    </main>
  </div>
</div>

<!-- jogosultság változtatása script -->
<script type="text/javascript">
$('td').on('change', function() {
  var sorid = $(this).parent().find('td:first-child').text();
  var projektid = $(this).parent().find('td:nth-child(2)').text();
  var jogosultsag = $(this).parent().find('#jogosultsagok option:selected').val();
  $.ajax({
      type: "POST",
      url: "includes/savejogosultsag.inc.php",
      data : {jog:jogosultsag, id:sorid, pid:projektid},
      success: function(response)
      {
        if(response == 1){
          alert('Sikeres változtatás.');
        }else if(response == 0){
            alert('Nem megfelelő id.');
        }else{
            alert('Sikertelen változtatás.');
        }
      }
  });
});
</script>

<?php
    require "footer.php";
?>
