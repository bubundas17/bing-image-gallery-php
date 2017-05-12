<?php
 /**
    Hi i am Bubun and it is the first and full php project i have done (Sorry not "done", It should be "Uploaded in Github") in my life (LOL).
     It is basicly a php class with all needed functions to download daily images from bing and index them.
     It dont need any database. just upload the file in your server and it should be good to go.
     Thank You Guyes.
**/

/**
  HOW TO USE?
  Just upload this script to your server and add a crone job and run import.php once in a day.
  It'll auto import images from bing and save them in server.
**/

// Class For image listing
  class BingHelper{
    // public $imagedir  = "images/";
    public $bing     = "https://www.bing.com";
     // Bing api url
    public $bingAPI  = "https://www.bing.com/HPImageArchive.aspx";
    public $directory = "images/";   // directory for Saving Images.


    function imageList($q = ""){
      $directory = $this->directory;
        // put all files in an array
      $dir = glob($directory.'*'.$q.'*.jpg');
        // Return false if the directory is empty or somthing wrong in Getting the file list.
      if(! $dir) return false;
        // Lets short the file list by date.
      uasort($dir, function($a, $b) {
        return filemtime($a) < filemtime($b);
      });
      // print_r($out);
      return $dir;
    }


    function getImageDate($img){
      // Getting image size from local file and return the value
      if (file_exists($img)) {
        $date=date ("F d, Y", filemtime($img));
        return $date;
      } else {
        // Return false if the file is not exlists.
        return false;
      }
    }

    // Got this function on stackoverfollow thank you guyes :)
    function sizeFilter($file){
        // Lets take the file size of the local file.
      $bytes = filesize($file);
        // Lets create a list of possible file sizes and store them in an array.
      $label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB', 'ZB');
        // loop through and devide with 1024 each time (If grather than 1024).
      for( $i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++ );
        // And finally round out the value and also return it for later use.
      return( round( $bytes, 2 ) . " " . $label[$i] );
    }

      // This function is used to make list of all local inages and store in an array.
    function locImgList($q = "",  $p=1, $n=16){
      $p--;
      $arr=array();
      $finalMap=array();
      // Return false if directory empty.
      if(!$this->imageList($q)) return false;
        // Lets formate the array a little bit :)
      foreach ($this->imageList($q) as $img) {
        $arr[]  = array(
            "imgPath"    =>  $img,
            "imgName"    =>  basename($img, '.jpg'),
            "dateAdded"  =>  $this->getImageDate($img),
            "size"       =>  $this->sizeFilter($img),
            "quality"    =>  getimagesize($img)[1].'P',
            "thumbnail"  =>  "thumbnail.php?img=".base64_encode($img)
        );
      }
        // Brake the array intu chunks for pagenation
      $arrayc = array_chunk($arr, $n);
      $finalMap["contents"]  = $arrayc[$p];
        // Sone other informations needed for pagenation
      $finalMap["pageNo"]    = $p++;
      $finalMap["pages"]     = count($arrayc);
      return $finalMap;
    }

      // Okey here is the main consept of this Walpaper gallery. Get image from bing and save them in daily basis and display them in main site.
    function bingImageList($n=1, $mkt = "en-IN"){
      // Api quarys for getting bing image list :)
      $q  = http_build_query(array(
            "format"  =>  "js",
            "idx"     =>  0,
            "n"       =>  $n,
            "mkt"     =>  $mkt
          ));

          // Download the api json file.
        $json =  file_get_contents($this->bingAPI.'?'.$q);
          // Converting the json file to array that we can use the array.
        $json = json_decode($json,true);
          // Lets formate and cleanup up the array a little bit.
        $json = $this->formatArr($json);
        // Return Return Return :)
        return $json;
    }

    // Function to save bing image for later use.
    function saveImage($n = 1){
        $dir  = $this->directory;
        $bingImageList = $this-> bingImageList($n);
        foreach ($bingImageList as $image) {
        $error  = false;
        $data = file_get_contents($image['url']);
        $name = $image;
        $name = basename($name.'.jpg');
          if (!file_put_contents($dir.$name, $data)) {
            $error = true;
          }
        }
      return $error;
    }
    // Function for pagenate  through pages.
    function pagenation($p=1, $n=1){
        // I cant fully explain what i am doing here, But it works !!!
          // Oh and just for referance, $p is the number of cuttent page while $n is is the number of total pages.
        // Return false if there is no pages to pagenete.
      if ($n <= 1) {
        return false;
      }

        // I am using Boostrap here, please chenge the codes according to your needs. :)
      echo '<nav aria-label="Page navigation">';
      echo '<ul class="pagination">';
      if($p<=1){
        echo '<li class="disabled">';
        echo '<a aria-label="Previous">';
      } else {
        echo '<li>';
        echo '<a href="?p='.($p-1).'" aria-label="Previous">';
      }
      echo '<span aria-hidden="true">&laquo;</span>';
      echo '</a>';
      echo '</li>';
      $j=1;
      $k=1;
        // A crazy looking loop to skip unneeded parts.
      for($i=1; $i  <= $n; $i++){
        if ($i > 1 && $i < ($p-1)){
          if($j==1){
            echo '<li><a>...</a></li>';
            $j++;
          }
          continue;
        } else if( ($p+1) < $i && $i < ($n)){
          if($k==1){
            echo '<li><a>...</a></li>';
            $k++;
          }
          continue;
        }

        if($p==$i){
          echo '<li class="active"><a>'.$i.'<span class="sr-only">(current)</a></span></li>';
        } else {
          echo '<li><a href="?p='.$i.'">'.$i.'</a></li>';
        }
      }
      if($p>=$n){
        echo '<li class="disabled">';
        echo '<a aria-label="Next">';
      } else {
        echo '<li>';
        echo '<a href="?p='.($p+1).'" aria-label="Next">';
      }
      echo '<span aria-hidden="true">&raquo;</span>';
      echo '</a>';
      echo '</li>';
      echo '</ul>';
      echo '</nav>';
      return 1;
    }
      // Array formating :)
    function formatArr($arr){
      $array = array();
      foreach($arr["images"] as $image){
        $name = $image["copyright"];
        $name = explode(' (©',$name)[0];
        $name = str_replace(',',' -', $name);
        $hash = $image['hsh'];
        $url  = $this->bing.$image['url'];
        $array[] = array(
          'name'  =>  $name,
          'url'   =>   $url,
          'hash'  =>  $hash
          );
      }
      return $array;
    }

  }

?>
