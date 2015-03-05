<?php
header("Content-Type: image/jpeg");
$DEFAULT_URL = "https://www.tinhte.vn/styles/sonnb/XenGallery/camera-no-image.jpg";
        
//declare image sizes available
$imgType = ["z","y","b","l","s","d","e","g","n","t"];

$imgUrl = base64_decode($_GET["img_url"]);

$imgUrl = getMaxImage($imgUrl);

$c = file_get_contents($imgUrl);
$arr = getimagesizefromstring($c);

$img = imagecreatefromstring($c);

if (!is_array($arr)) {
    //remote image is not available. Use default one.
    $c = file_get_contents($DEFAULT_URL);
}

if (isset($_GET["width"])){
  //Get Width and Height
  List($Width, $Height) = getimagesize($imgUrl);

   //Calc new Size
  $w = $_GET["width"];
  if ($w > $Width){
      $w = $Width;
  }
  
  $h = $Height * ($w / $Width);

  //Build the image
  //Create new Base
  $NewImageBase = imagecreatetruecolor($w, $h);

  //copy image
  imagecopyresampled($NewImageBase, $img, 0, 0, 0, 0, $w, $h, $Width, $Height);
  $img = $NewImageBase;
}

//display image
imagejpeg($img);

//support functions

function changeImageType($imageUrl, $type){
    $length = strlen($imageUrl);
    $imageUrl[$length-5] = $type;
    return $imageUrl;
}

function tryImage($imageUrl){
    if (true==file_get_contents($imageUrl,0,null,0,1)) {
        return true;
    }
    return false;
}

function getMaxImage($imageUrl){
    global $imgType, $DEFAULT_URL;

    foreach($imgType as $type){
        $imageUrl = changeImageType($imageUrl, $type);
            if(tryImage($imageUrl)){
                return $imageUrl;
            }
    }
    return $DEFAULT_URL;
}

?>
