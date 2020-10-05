<?php
// Include the database configuration file
require 'dbh.inc.php';
session_start();

$editorContent = '';
$pid = $_SESSION['projektId'];

// If the form is submitted
if(isset($_POST['mtsubmit'])){
    // Get editor content
    $editorContent = $_POST['mteditor'];

    // Check whether the editor content is empty
    if(!empty($editorContent)){
        // Insert editor content in the database
        $checkRecord = mysqli_query($conn,"SELECT * FROM muszakitartalom WHERE projekt_id=".$pid);
        $totalrows = mysqli_num_rows($checkRecord);

        if ($totalrows > 0) {
          $stmt = $conn->query("UPDATE muszakitartalom SET
                      tartalom ='$editorContent' WHERE projekt_id='$pid'");

          //$successfullyCopied = $stmt->execute();

        }
        else {
          $stmt = $conn->query("INSERT INTO muszakitartalom (projekt_id, tartalom)
                                              VALUES ('$pid','$editorContent')");
          //$successfullyCopied = $stmt->execute();
        }

        /*$insert = $conn->query("INSERT INTO muszakitartalom (projekt_id, tartalom)
                                  VALUES ('$pid','$editorContent')");*/

        // If database insertion is successful

        require_once 'naplo.inc.php';
        $szoveg = ("insert/update muszakitartalom");
        naplozas($szoveg);

        if($stmt){
          //  $statusMsg = "The editor content has been inserted successfully.";
          header("Location: ../muszakitartalom.php?sikeresfelvetel");
          exit();
        }else{
            //$statusMsg = "Some problem occurred, please try again.";
            header("Location: ../muszakitartalom.php?nemsikerultafelvetel");
            exit();
        }
    }else{
      //  $statusMsg = 'Please add content in the editor.';
      header("Location: ../muszakitartalom.php?adjonmegszoveget");
      exit();
    }
}
header("Location: ../muszakitartalom.php?probaljamegmegegyszer");
exit();
?>
