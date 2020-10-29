<?php
require 'dbh.inc.php';
session_start();

$output = '';
if (isset($_POST["excelexport"])) {
  $pid = $_SESSION['projektId'];
  $sql = "SELECT * FROM helyi_anyaglista
        INNER JOIN pa_kapcsolat
          ON helyi_anyaglista.helyi_anyaglista_id = pa_kapcsolat.alkatresz_id
        INNER JOIN projekt
          ON pa_kapcsolat.projekt_id = projekt.projekt_id
          WHERE projekt.projekt_id = '$pid'
          ORDER BY helyi_anyaglista.helyi_anyaglista_id";

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
                             <td>'.$row["helyi_anyaglista_megnevezes"].'</td>
                             <td>'.$row["helyi_anyaglista_sapszam"].'</td>
                             <td>'.$row["helyi_anyaglista_mertekegyseg"].'</td>
                             <td>'.$row["helyi_anyaglista_egysegar"].'</td>
                             <td>'.$row["pa_dbszam"].'</td>
                             <td>'.$row["pa_dbszam"]*$row["helyi_anyaglista_egysegar"].'</td>
                        </tr>
       ';

       $sorar=$row["pa_dbszam"]*$row["Egysegar"];
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
