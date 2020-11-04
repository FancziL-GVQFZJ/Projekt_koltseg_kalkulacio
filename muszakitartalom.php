<?php
    $thisPage='Kalkulacioslap';
    $thisPage1='Muszaki';
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
        if (isset($_SESSION['userId']) && isset($_SESSION['projektId']) && ($jogosultsag == 'iras' || $jogosultsag == 'admin')) { ?>
          
          <?php
          $pid = $_SESSION['projektId'];
          $result = mysqli_query($conn,"SELECT * FROM muszakitartalom where projekt_id='$pid'");
          $row = mysqli_fetch_array($result);
          $leiras=$row['muszakitartalom_tartalom'];
          ?>

          <form method="post" action="includes/databaseinsert/insertmt.inc.php">
            <textarea id="mteditor" name="mteditor"><?php echo $leiras?></textarea>
            <input type="submit" name="mtsubmit" value="Mentés">
          </form>

          <?php }
        else {
          echo '<p>Jelenleg ki van jelentkezve!</p>';
        }
        ?>
      </div>
    </main>
  </div>
</div>

<script type="text/javascript">
 CKEDITOR.replace('mteditor',{
   margin: "2%",
 });

</script>

<?php
    require "footer.php";
?>
