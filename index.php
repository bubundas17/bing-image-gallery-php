
<?php
 /**
    Hi i am Bubun and it is the first and full php project i have done (Sorry not "done", It should be "Uploaded in Github") in my life (LOL).
     It is basicly a php class with all needed functions to download daily images from bing and index them.
     It dont need any database. just upload the file in your server and it should be good to go.
     Thank You Guyes.
**/
/**
Made By Bubun.
My Blog: Https Techiey.com
FB: https://fb.me/bubundas17
Please Do Suggest if any bugs or needs any feature to be added.
**/
/**
  HOW TO USE?
  Just upload this script to your server and add a crone job and run import.php once in a day.
  It'll auto import images from bing and save them in server.
**/

  include "inc/header.php";
  include "inc/func.php";
  $img=new BingHelper();
  if(empty($_GET['p'])){
    $page = 1 ;
  } else {
    $page = $_GET['p'];
  }
  $imageList=$img->locImgList($_GET['q'] ,$page,  16);
  // print_r($imageList);

  echo '<div class="container" >';
  echo '<div class="row">';
  echo '<div class="list-group">';
  if($imageList) {
    foreach ( $imageList["contents"] as $image) {
      echo '<div class="col-lg-3 col-md-4 col-sm-6 group">';
      echo '<div class="list-group-item">';
      echo '<div class="row">';
      echo '<div class="col-sm-12 col-xs-4">';
      echo '<center>';
      echo '<div class="thumbnail" >';
      echo '<img src="'.$image["thumbnail"].'" >';
      echo '</div>';
      echo '</center>';
      echo '</div>';
      echo '<div class="col-sm-12 col-xs-8">';
      echo '<p class="box">';
      echo '<b> Name: </b>'.$image["imgName"];
      echo '</p>';
      echo '<p class="box">';
      echo '<b> Added: </b>'.$image["dateAdded"];
      echo '</p>';
      echo '<p class="box">';
      echo '<b> Size: </b>'.$image["size"];
      echo '</p>';
      echo '<p class="box">';
      echo '<b> Quality: </b>'.$image["quality"];
      echo '</p>';
      echo '<div class="btn-group">';
      echo '<a type="button" class="btn btn-success" href="'.$image["imgPath"].'">Download</a>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    // print_r($img->outMap());
      }
    } else {
      echo 'No Results Found';
    }
  echo '</div>';
  echo '</div>';
  echo '<div class="row">';
  echo '<div class="col-lg-12">';
  echo '<center>';
  $img->pagenation($page, $imageList["pages"]);
  echo '</center>';
  echo '</div>';
  echo '</div>';
  include "inc/footer.php";
?>
