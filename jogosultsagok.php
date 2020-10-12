<?php
    $thisPage='Kezdooldal';
    require "header.php";
    session_start();
?>
<style><?php include 'css/navbar.css';?></style>
<style><?php include 'css/table.css';?></style>

<div id="container">
  <div id="main">
    <main>
      <?php
        if (isset($_SESSION['userId'])) {
          require 'includes/dbh.inc.php';
          echo '<nav class="topnav">
            <ul>
              <li><a href="index.php">Projektek</a></li>
              <li><a style="background-color: #ddd;" href="#">Jogosultságok</a></li>
              <li><a href="munkadijak.php">Munkadíjak</a></li>
              <li><a href="naplo.php">Naplo</a></li>
            </ul>
          </nav>';

          echo "<div class='kezdolap'>";
            echo "<div class='lap'>";
              echo '<p>Projektjeid:</p>';

              $fid = $_SESSION['userId'];
              $sql = "SELECT idProjekt, projektNev FROM projekt
                    INNER JOIN pf_kapcsolat
                      ON projekt.idProjekt = pf_kapcsolat.projektId
                    INNER JOIN users
                      ON pf_kapcsolat.userId = users.idUsers
                      WHERE users.idUsers = $fid";

              $sor=mysqli_query($conn, $sql);

              echo "<table>";
              echo "<tr>";
              while ($row=mysqli_fetch_array($sor))
              {
                $sorid=$row['idProjekt'];
                $pnev=$row['projektNev'];
                echo "<tr>";
                echo "<td>".$row['idProjekt']."</td>";
                echo "<td>".$row['projektNev']."</td>";
                ?>
                <td>
                  <form action="" method="post">
                  <input type="hidden" name="projektnev" value="<?php echo htmlentities($pnev); ?>">
                  <input type="hidden" name="projektid" value="<?php echo htmlentities($sorid); ?>">
                  <button type="submit" name="projektjog">beállítás</button>
                  </form>
                </td>
                <?php
                echo "</tr>";
              }
              echo "</table>";

              if(isset($_REQUEST['projektjog']))
              {
                $jpnev = $_POST["projektnev"];
                $jpid = $_POST["projektid"];


                $fnevek = "SELECT * FROM users WHERE NOT idUsers ='$fid'";
                $sor2=mysqli_query($conn, $fnevek);
                echo "<br><br>";
                echo $jpnev." beállítása";

                echo "<table id='table'>";
                echo "<tr>";
                echo "<th>Id</th><th>ProjektId</th><th>Felhasználó</th><th>Jogosultság</th>";
                while ($row2=mysqli_fetch_array($sor2))
                {
                  $fnev=$row2['idUsers'];
                  $jogosultsagok = mysqli_query($conn,"SELECT iras, olvasas FROM jogosultsag
                                INNER JOIN users
                                ON jogosultsag.user_id = users.idUsers
                                WHERE jogosultsag.user_id ='$fnev' AND jogosultsag.projekt_id ='$jpid'");
                  $row3=mysqli_fetch_array($jogosultsagok);
                  echo "<tr>";
                  echo "<td>".$row2['idUsers']."</td>";
                  echo "<td>".$jpid."</td>";
                  echo "<td>".$row2['uidUsers']."</td>";
                  if ($row3['iras']==1 && $row3['olvasas']==1) {
                    echo "<td><select name='jogosultsagok' id='jogosultsagok'>
                      <option value='0' ></option>
                      <option value='1' selected>Írás</option>
                      <option value='2' >Olvasás</option>
                    </select>
                    </td>";
                  }
                  else if ($row3['iras']==0 && $row3['olvasas']==1) {
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
                }
                echo "</table>";
              }
            echo "</div>";
          echo "</div>";
        }
        else {
          echo '<p>You are logged out!</p>';
        }
      ?>
    </main>
  </div>
</div>

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
  console.log(jogosultsag);
});
</script>

<?php
    require "footer.php";
?>
