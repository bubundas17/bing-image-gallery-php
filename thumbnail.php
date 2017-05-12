<?php
/**
   Made By Bubun.
   My Blog: Https Techiey.com
   FB: https://fb.me/bubundas17
   Please Do Suggest if any bugs or needs any feature to be added.
**/
//include "MD.php";
//$mob= new Mobile_Detect();
header('Content-Type: image/jpeg');
// for jpg
function resizeImagejpg($file, $w, $h) {
   list($width, $height) = getimagesize($file);
   $src = imagecreatefromjpeg($file);
   $dst = imagecreatetruecolor($w, $h);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
   return $dst;
}

 // for png
function resizeImagepng($file, $w, $h) {
   list($width, $height) = getimagesize($file);
   $src = imagecreatefrompng($file);
   $dst = imagecreatetruecolor($w, $h);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
   return $dst;
}

// for gif
function resizeImagegif($file, $w, $h) {
   list($width, $height) = getimagesize($file);
   $src = imagecreatefromgif($file);
   $dst = imagecreatetruecolor($w, $h);
   imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $width, $height);
   return $dst;
}

$image = base64_decode($_GET['img']);
$data  = resizeImagejpg($image,177,100);
imagejpeg($data,null,100);
imagedestroy($data);

?>
