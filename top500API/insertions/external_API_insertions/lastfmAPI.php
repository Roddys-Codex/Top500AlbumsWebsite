<?php

//get artist photo
function getArtistPhoto($artist, $size) {

                $artist = urlencode($artist);
                $xml    = "http://ws.audioscrobbler.com/2.0/?method=artist.getinfo&artist={$artist}&api_key=YOUR_KEY";
                $xml    = @file_get_contents($xml);
                
                if(!$xml) {
                        return;  // Artist lookup failed.
                }
                
                $xml = new SimpleXMLElement($xml);
                $xml = $xml->artist;
                $xml = $xml->image[$size];
                
                $return = convert($xml);             

                return $return;
}                               

function getArtistAlbums($artist, $size) {

                $artist = urlencode($artist);
                $xml    = "http://ws.audioscrobbler.com/2.0/?method=artist.gettopalbums&artist={$artist}&api_key=YOUR_KEY";
                $xml    = @file_get_contents($xml);
                
                if(!$xml) {
                        return;  // Artist lookup failed.
                }
                
                $xml = new SimpleXMLElement($xml);
                $xml = $xml->topalbums;
                foreach ($xml->album as $album) {
                        $album_img =  $album->image[$size];
                        $album_image = convert($album_img);
                        $album_name =  $album->name;

                        //echo instead of returning
                        echo $album_name."<br>".$album_image."<br><br>";
                }

}  

function getAlbum($artist, $album, $size) {

                

                $artist = urlencode($artist);
                $album = urlencode($album);
                $xml    = "http://ws.audioscrobbler.com/2.0/?method=album.getinfo&artist={$artist}&album={$album}&api_key=YOUR_KEY";
                $xml    = @file_get_contents($xml);
                
                if(!$xml) {
                        return;  // Artist lookup failed.
                }
                
                $xml = new SimpleXMLElement($xml);
                $xml = $xml->album;
                $xml = $xml->image[$size];
                
                $return = convert($xml);             

                return $return;

}  

function convert($file){

        $parts=pathinfo($file);
        //dont convert if its a jpg
        if($parts['extension'] == "jpg"){ 
                return '<img src="' . $file . '" />';
        } else {

        $image = imagecreatefrompng($file);
        $bg = imagecreatetruecolor(imagesx($image), imagesy($image));
        imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
        imagealphablending($bg, TRUE);
        imagecopy($bg, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));

        ob_start (); 
        imagejpeg($bg, NULL, 80);
        $image_data = ob_get_contents (); 
        ob_end_clean (); 
        $imageData = base64_encode ($image_data);

        imagedestroy($image);
        ImageDestroy($bg);

        return '<img src="data:image/jpg;base64,'.$imageData.'" />';

        }

}

$artist = $_GET['artist'];
$album = $_GET['album'];
//$size_map = array("small" => 0, "medium" => 1, "large" => 2, "extralarge" => 3, "mega" => 4);

if($album && $artist){

        //get album cover
        echo "<h2>".$artist."</h2><br><h3>".$album."</h3>";
        $album_image = getAlbum($artist,$album,3);
        if($album_image) {
                echo $album_image."<br>";
        }

} else if($artist){

        //get artist photo
        echo "<h2>".$artist."</h2><br>";
        $artist_image = getArtistPhoto($artist,3);
        if($artist_image) {
                echo $artist_image."<br>";
        }
        getArtistAlbums($artist,3);

} else {
        echo '<h1>Grab Album and Artist Images from Last.FM API | TechSlides</h1><br>Back to <a href="http://techslides.com/lastfm-api-with-php/">article</a><br><br>Please specify artist or artist and album. Here are 2 examples:<br><a href="http://techslides.com/demos/lastfm_artwork.php?artist=Adele">http://techslides.com/demos/lastfm_artwork.php?artist=Adele</a><br><a href="http://techslides.com/demos/lastfm_artwork.php?artist=Adele&album=21">http://techslides.com/demos/lastfm_artwork.php?artist=Adele&album=21</a>';
}


?>