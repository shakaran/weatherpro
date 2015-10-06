<?php
//header("Location: http://$_SERVER[HTTP_HOST]/wxwugraphs.php");
include_once('./WUG-settings.php');
if ($standAlone){
  header("Location: ../wugraphs.php");
} else {
  header("Location: ../wxwugraphs.php");
}
?>
