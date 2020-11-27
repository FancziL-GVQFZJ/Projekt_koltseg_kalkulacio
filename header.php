<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <link href="css/navbar.css" rel="stylesheet" type="text/css">
    <link href="css/table.css" rel="stylesheet" type="text/css">
    <meta charset="utf-8">
    <meta name="description" content="Projekt költség kalkulációs rendszer.">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfromcart.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfrommd.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfrommdij.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfrompmdij.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfromek.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfromak.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/delfromha.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/addtocart.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/addtohelyi.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/startprojekt.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/stopprojekt.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/deleteprojekt.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/removeprojekt.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/filter_table.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/filter_table1.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/jquery.tabledit.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/js/jquery-3.5.1.js"></script>
    <script src="/Projekt_koltseg_kalkulacio/ckeditor/ckeditor.js"></script>
    <title>Projekt költség kalkulációs rendszer</title>
    <meta charset="UTF-8">
  </head>

<div class="border">
  <body>
    <?php
    if (isset($_SESSION['userId'])) {
      require 'includes/kapcsolat.inc.php';
      $fid = $_SESSION['userId'];
      $fnev = $_SESSION['userName'];
      $pid = $_SESSION['projektId'];
      $tema = $_SESSION['tema'];
      $jogosultsag = $_SESSION['jogosultsag'];
      $thisPage == 'Kezdooldal';?>
      <header>
        <!-- téma kiválasztása -->
        <div class="select">
          <?php
          if ($tema==1) { ?>
          <link href="css/theme1.css" rel="stylesheet" type="text/css">
            <style><?php require "css/theme1.css";?></style>
            <select name="tema" id="tema" onchange="tema(this)">
              <option value="1" selected>Világos</option>
              <option value="2">Sötét</option>
            </select>
          <?php }elseif ($tema==2) { ?>
            <link href="css/theme2.css" rel="stylesheet" type="text/css">
            <style><?php require "css/theme2.css";?></style>
            <select name="tema" id="tema" onchange="tema(this)">
              <option value="1">Világos</option>
              <option value="2" selected>Sötét</option>
            </select>
          <?php }else { ?>
            <link href="css/theme1.css" rel="stylesheet" type="text/css">
            <style><?php require "css/theme1.css";?></style>
            <select name="tema" id="tema" onchange="tema(this)">
              <option value="1" selected>Világos</option>
              <option value="2">Sötét</option>
            </select>
          <?php } ?>
        </div>
        <!-- projekt neve kiírása -->
        <div class="projektnev-container">
          <a href="#">
            <img src="img/vmlogo.png" alt="logo" class="logo">
          </a>
          <?php
          if (isset($_SESSION['projektId'])) {
            $projektneve = $_SESSION['projektNeve'];
            echo '<p>Jelenlegi projekt: <b style="font-size: 22px;">'.$projektneve.'</b>('.$jogosultsag.')</p>';
          }else {
            echo '<p><b>Projekt költség kalkulációs rendszer</b></p>';
          }
          ?>
        </div>
        <!-- felső navigációs sáv -->
        <nav class="topnav">
          <ul>
            <li><a <?php echo ($thisPage == 'Kezdooldal') ? ' class="selected"' : ''; ?> href="index.php">Kezdőlap</a></li>
            <li><a <?php echo ($thisPage == 'Anyaglista') ? ' class="selected"' : ''; ?> href="sap_anyaglista.php">Villamos anyaglista</a></li>
          <?php if (isset($_SESSION['projektId'])) { ?>
            <li><a <?php echo ($thisPage == 'Kalkulacioslap') ? ' class="selected"' : ''; ?> href="kalkulacioslap.php">Kalkulációs lap</a></li>
          <?php } ?>
          </ul>
           <!-- kijelentkező gomb -->
          <div class="logout-container">
            <form action="includes/logout.inc.php" method="post">
              <p>Jelenlegi felhasználó: <b><?php echo $fnev ?></b></p>
              <button type="submit" name="logout-submit">Kijelentkezés</button>
            </form>
          </div>
        </nav>
      </header>
      <!-- bejelentkező felület ha nincs bejelentkezve a felhazsnáló -->
      <?php } else { ?>
        <div class="bejelentkezofelulet">
          <link href="css/theme1.css" rel="stylesheet" type="text/css">
          <div class="cim-container">
            <p>Pojekt költség kalkulációs rendszer</p>
          </div>

          <div class="login-container">
            <form class="login-form" action="includes/login.inc.php" method="post">
              <label>Felhasználónév:</label><br>
              <input type="text" name="mailuid" placeholder="A felhasználóneved..."><br>
              <label>Jelszó:</label><br>
              <input type="password" name="pwd" placeholder="A jelszavad..."><br>
              <button style="" type="submit" name="login-submit">Bejelentkezés</button>
            </form>
          </div>
        </div>
      <?php } ?>
  </body>
</html>

<!-- téma változtatása ajaxal -->
<script type="text/javascript">
$('#tema').on('change', function() {
  var e = document.getElementById("tema");
  var tema = e.value;
  $.ajax({
      type: "POST",
      url: "includes/tema.inc.php",
      data : {id:tema},
      success: function(response)
      {
        if(response == 1){
          window.location.reload();
        }else if(response == 0){
            alert('Nem megfelelő id.');
        }else{
            alert('Sikertelen változtatás.');
        }
      }
  });
});
</script>
