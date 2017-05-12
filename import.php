<?php
header("Content-Type: text/plain");
include "inc/func.php";
$i = new BingHelper();
// $i->getImageList()
print_r($i->saveImage(2));
?>
