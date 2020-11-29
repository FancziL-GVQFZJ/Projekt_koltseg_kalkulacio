<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/kapcsolat.inc.php';
session_start();

$editorContent = '';
$pid = $_SESSION['projektId'];

// műszaki tartalmat menti el

if(isset($_POST['mtsubmit'])){
    $editorContent = $_POST['mteditor'];

    if(!empty($editorContent)){
        $checkRecord = mysqli_query($conn,"SELECT * FROM muszakitartalom WHERE projekt_id=".$pid);
        $totalrows = mysqli_num_rows($checkRecord);

        if ($totalrows > 0) {
          $stmt = $conn->query("UPDATE muszakitartalom SET
                      muszakitartalom_tartalom ='$editorContent' WHERE projekt_id='$pid'");

        }
        else {
          $stmt = $conn->query("INSERT INTO muszakitartalom (projekt_id, muszakitartalom_tartalom)
                                              VALUES ('$pid','$editorContent')");
        }

        require $_SERVER['DOCUMENT_ROOT'] . '/Projekt_koltseg_kalkulacio/includes/naplo.inc.php';
        $szoveg = ("insert/update muszakitartalom");
        naplozas($szoveg);

        if($stmt){
          header("Location: ../../muszakitartalom.php?sikeresfelvetel");
          exit();
        }else{
            header("Location: ../../muszakitartalom.php?nemsikerultafelvetel");
            exit();
        }
    }else{
      header("Location: ../../muszakitartalom.php?adjonmegszoveget");
      exit();
    }
}
header("Location: ../../muszakitartalom.php?probaljamegmegegyszer");
exit();
?>
