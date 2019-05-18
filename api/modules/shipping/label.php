<?php
$imageWidth= 380;
$imageHeight= 700;
$text="the quick brown fox jump over the lazy dog. Lorem ipsum dolor sit amet consectetuer adipsicing elit.";
$fontpath = __DIR__ . "/DejaVuSansMono.ttf";
$fontpathBold = __DIR__ . "/DejaVuSansMono-Bold.ttf";
$sourceLogo = __DIR__ . "/logo.png";
$destPath = __DIR__ . "/../../sys/printer/logo/shipping-label.png";
$textLine = 0;
$nama= "Anggellia Fisca Ayu Adista";
$alamatCharWidth=30;
$alamat= "089681364786
Perum Kebon Agung Mas B1 No 3
Purworejo
Kota Pasuruan, Jawa Timur 67116";
$alamatWrapped= explode("\n",wordwrap($alamat,$alamatCharWidth));


header('Content-type:image/png');
//create image
$png_image=imagecreatetruecolor($imageWidth,$imageHeight);
//colors
$bgcolor = imagecolorallocate($png_image, 255, 255, 255);
$black=imagecolorallocate($png_image,0,0,0);
//background
imagefill($png_image,0,0,$bgcolor);
//thiccness
imagesetthickness($png_image,1);
//rectangle(all)
imagerectangle($png_image, 0, 0, $imageWidth-1,$imageHeight-1, $black);
//rectangle(dari)
imagerectangle($png_image, 280, 110, $imageWidth-1,$imageHeight-1, $black);
//rectangle(kepada)
imagerectangle($png_image, 0, 110, 280, $imageHeight-1, $black);

//-----------logo------
$logo = imagecreatefrompng($sourceLogo);
imagecopy($png_image, $logo, 10, 5, 0, 0, 359, 102);

//text
//Dari
imagettftext($png_image,18,270,textLine(24),115,$black,$fontpathBold,"Pengirim:");
//gemastore
imagettftext($png_image,24,270,textLine(32),115,$black,$fontpath,"GemaStore (0857-4966-1649)");
//pasuruan
imagettftext($png_image,24,270,textLine(32),115,$black,$fontpath,"Kab. Pasuruan");
//Penerima
imagettftext($png_image,18,270,textLine(38),115,$black,$fontpathBold,"Penerima:");
//nama
imagettftext($png_image,24,270,textLine(32),115,$black,$fontpath,$nama);
//Alamat
// imagettftext($png_image,18,270,textLine(30),115,$black,$fontpathBold,"Alamat:");
textLine(8);
//alamat
foreach($alamatWrapped as $alamatLine){
    imagettftext($png_image,24,270,textLine(32),115,$black,$fontpath,$alamatLine);
}

imagepng($png_image);
imagepng($png_image,$destPath);
imagedestroy($png_image);

function textLine($height){
    global $textLine,$imageWidth;
    $textLine+=$height;
    return $imageWidth - ($textLine);
}