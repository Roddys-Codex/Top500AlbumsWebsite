<?php
$api_response = array();
$notSuccessfulArray = array();
$unsuccessfulCounter = 0;

// ALL ALBUMS
$endpointAll = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?albumAndArtist";

$resourceAll = file_get_contents($endpointAll, false, stream_context_create());

$dataAll = json_decode($resourceAll, true);

while ($dataAll != false) {

    $row = array_shift($dataAll);

    $album = $row['album_name'];
    $artist = $row['artist_name'];

    $albumURL = urlencode($album);
    $artistURL = urlencode($artist);

    $url = "https://api.spotify.com/v1/search?q=album:$albumURL+artist:$artistURL&type=album,artist,playlist&include_external=audio";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Accept: application/json",
        "Authorization: Bearer BQD0dW63QWkCWoWUv2sA_8DUq9v72tv711ooNZDVewT_7l_99xaPES4MrMiAz0AsmOiqxp2dQtBbLUh0L1dRj4PKAyCdf3Iya67It8_ujOf4b0347Z4tRR2rdqafRGBqVCP2vxLGXm6vFFaktw4Sf35Xq3niXsF5cPdgrzBBl2hPnrB3gaKJahCvRvJNLyM-xg",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);

    // build a response array
    $json = json_decode($resp, true);
    array_push($api_response, $resp);

    if(isset($json['albums']['items'][0]['images'][0]['url'])) {

        $imgOne = $json['albums']['items'][0]['images'][0]['url'];
        
    } else if(isset($json['albums']['items'][0]['images'][1]['url'])){

        $imgOne = $json['albums']['items'][0]['images'][1]['url'];
        
    } else if(isset($json['albums']['items'][0]['images'][2]['url'])){

        $imgOne = $json['albums']['items'][0]['images'][2]['url'];
        
    } else if(isset($json['albums']['items'][0]['images'][3]['url'])){

        $imgOne = $json['albums']['items'][0]['images'][3]['url'];

    } else if(isset($json['albums']['items'][0]['images'][4]['url'])){

        $imgOne = $json['albums']['items'][0]['images'][4]['url'];

    } else if(isset($json['playlist']['items'][0]['images'][0]['url'])) {

        $imgOne = $json['playlist']['items'][0]['images'][0]['url'];
        
    } else if(isset($json['playlist']['items'][0]['images'][1]['url'])){

        $imgOne = $json['playlist']['items'][0]['images'][1]['url'];
        
    } else if(isset($json['playlist']['items'][0]['images'][2]['url'])){

        $imgOne = $json['playlist']['items'][0]['images'][2]['url'];
        
    } else if(isset($json['playlist']['items'][0]['images'][3]['url'])){

        $imgOne = $json['playlist']['items'][0]['images'][3]['url'];

    } else if(isset($json['playlist']['items'][0]['images'][4]['url'])){

        $imgOne = $json['playlist']['items'][0]['images'][4]['url'];

    } else if(isset($json['artist']['items'][0]['images'][0]['url'])) {

        $imgOne = $json['artist']['items'][0]['images'][0]['url'];
        
    } else if(isset($json['artist']['items'][0]['images'][1]['url'])){

        $imgOne = $json['artist']['items'][0]['images'][1]['url'];
        
    } else if(isset($json['artist']['items'][0]['images'][2]['url'])){

        $imgOne = $json['artist']['items'][0]['images'][2]['url'];
        
    } else if(isset($json['artist']['items'][0]['images'][3]['url'])){

        $imgOne = $json['artist']['items'][0]['images'][3]['url'];

    } else if(isset($json['artist']['items'][0]['images'][4]['url'])){

        $imgOne = $json['artist']['items'][0]['images'][4]['url'];

    } else if(!isset($json)){

        $artistNotAdded = $artist;
        $albumNotAdded = $album;
        $albumIDnotAdded = $row['album_id'];

        $notSuccessfulArray[$unsuccessfulCounter]['album_name'] = $album;
        $notSuccessfulArray[$unsuccessfulCounter]['artist_name'] = $artist;
        $notSuccessfulArray[$unsuccessfulCounter]['album_id'] = $albumIDnotAdded;

    } 

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?addSpotifyAlbumImageURLs";

    $postdata = http_build_query(
        array(
            'spotifyImgURL1' => $imgOne,
            'albumName' => $album
        )
    );

    $opts = array(

        'http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content'=> $postdata
        )
    );

    $context = stream_context_create($opts);
    $resource = file_get_contents($endpoint, false, $context);
    
    $imgOne = null;
    
}
$response = json_encode($api_response);
print_r($response);
echo nl2br("\r\n");
echo nl2br("\r\n");
echo var_dump($notSuccessfulArray);

?>