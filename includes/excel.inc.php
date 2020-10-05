<?php
require 'dbh.inc.php';
session_start();

$output = '';
if (isset($_POST["excelexport"])) {
  $pid = $_SESSION['projektId'];
  $sql = "SELECT * FROM alkatresz
        INNER JOIN pa_kapcsolat
          ON alkatresz.id = pa_kapcsolat.alkatresz_id
        INNER JOIN projekt
          ON pa_kapcsolat.projekt_id = projekt.idProjekt
          WHERE projekt.idProjekt = $pid
          ORDER BY alkatresz.id";

  $result=mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    $output .= '
       <table class="table" bordered="1">
                        <tr>
                             <th></th>
                             <th>Megnevezés</th>
                             <th>SAPSzám</th>
                             <th>ME</th>
                             <th>Egység ár</th>
                             <th>Darabszám</th>
                             <th>Ár összesen</th>
                        </tr>
      ';
      $i=1;
      $sorar=0;
      $teljesar=0;
      while($row = mysqli_fetch_array($result))
      {
       $output .= '
                        <tr>
                             <td>'.$i.'</td>
                             <td>'.$row["Megnevezes"].'</td>
                             <td>'.$row["SAPSzam"].'</td>
                             <td>'.$row["ME"].'</td>
                             <td>'.$row["Egysegar"].'</td>
                             <td>'.$row["DBszam"].'</td>
                             <td>'.$row["DBszam"]*$row["Egysegar"].'</td>
                        </tr>
       ';

       $sorar=$row["DBszam"]*$row["Egysegar"];
       $teljesar=$teljesar+$sorar;
       $i++;
      }
      $output .= '
                       <tr>
                            <td colspan="6" align="right">Teljes ár</td>
                            <td>'.$teljesar.'</td>
                       </tr>
      ';
      $output .= '</table>';
      header('Content-Type: application/xls');
      header('Content-Disposition: attachment; filename=download.xls');
      echo $output;
  }

}

?>
