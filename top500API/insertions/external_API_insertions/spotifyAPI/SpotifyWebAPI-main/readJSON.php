<?php

$str = file_get_contents('spotifyAPIalbumsWithPlaylist.json');

$json = json_decode($str, true); // decode the JSON into an associative array

// echo '<pre>' . print_r($json, true) . '</pre>';
$imgOne = "";
$imgTwo = "";
$imgThree = "";

$albumsNotFound = array();
$albumCount = 1;
for($i = 0; $i < 500; $i++) {

    echo " NUM{$i} PICTURE:   ";

    if(isset($json[$i]['albums']['items'][0]['images'][0]['url'])) {

        $imgOne = $json[$i]['albums']['items'][0]['images'][0]['url'];
        
    } else if(isset($json[$i]['albums']['items'][0]['images'][1]['url'])){

        $imgOne = $json[$i]['albums']['items'][0]['images'][1]['url'];
        
    } else if(isset($json[$i]['albums']['items'][0]['images'][2]['url'])){

        $imgOne = $json[$i]['albums']['items'][0]['images'][2]['url'];
        
    } else if(isset($json[$i]['albums']['items'][0]['images'][3]['url'])){

        $imgOne = $json[$i]['albums']['items'][0]['images'][3]['url'];

    } else if(isset($json[$i]['albums']['items'][0]['images'][4]['url'])){

        $imgOne = $json[$i]['albums']['items'][0]['images'][4]['url'];
    } 

    if(isset($json[$i]['albums']['items'][1]['images'][0]['url'])) {

        $imgTwo = $json[$i]['albums']['items'][1]['images'][0]['url'];
        
    } else if(isset($json[$i]['albums']['items'][1]['images'][1]['url'])){

        $imgTwo = $json[$i]['albums']['items'][1]['images'][1]['url'];
        
    } else if(isset($json[$i]['albums']['items'][1]['images'][2]['url'])){

        $imgTwo = $json[$i]['albums']['items'][1]['images'][2]['url'];
        
    } else if(isset($json[$i]['albums']['items'][1]['images'][3]['url'])){

        $imgTwo = $json[$i]['albums']['items'][1]['images'][3]['url'];

    } else if(isset($json[$i]['albums']['items'][1]['images'][4]['url'])){

        $imgTwo = $json[$i]['albums']['items'][1]['images'][4]['url'];
    } 

    if(isset($json[$i]['albums']['items'][2]['images'][0]['url'])) {

        $imgThree = $json[$i]['albums']['items'][2]['images'][0]['url'];
        
    } else if(isset($json[$i]['albums']['items'][2]['images'][1]['url'])){

        $imgThree = $json[$i]['albums']['items'][2]['images'][1]['url'];
        
    } else if(isset($json[$i]['albums']['items'][2]['images'][2]['url'])){

        $imgThree = $json[$i]['albums']['items'][2]['images'][2]['url'];
        
    } else if(isset($json[$i]['albums']['items'][2]['images'][3]['url'])){

        $imgThree = $json[$i]['albums']['items'][2]['images'][3]['url'];

    } else if(isset($json[$i]['albums']['items'][2]['images'][4]['url'])){

        $imgThree = $json[$i]['albums']['items'][2]['images'][4]['url'];
    } 

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?addSpotifyAlbumImageURLs";

    $postdata = http_build_query(
        array(
            'spotifyImgURL1' => $imgOne,
            'spotifyImgURL2' => $imgTwo,
            'spotifyImgURL3' => $imgThree,
            'albumID' => $albumCount
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

    echo "   PICTURE 1:   ";
    echo nl2br("\r\n");
    echo $json[$i]['albums']['items'][1]['images'][0]['url'];
    echo $json[$i]['albums']['items'][1]['images'][1]['url'];
    echo $json[$i]['albums']['items'][1]['images'][2]['url'];
    echo nl2br("\r\n");
    echo nl2br("\r\n");
    echo nl2br("PICTURE INSERTED \r\n");


    // echo "   PICTURE 2:   ";
    // echo $json[$i]['albums']['items'][2]['images'][0]['url'];
    // echo $json[$i]['albums']['items'][2]['images'][1]['url'];
    // echo $json[$i]['albums']['items'][2]['images'][2]['url'];

    // echo "   PICTURE 3:   ";
    // echo $json[$i]['albums']['items'][3]['images'][0]['url'];
    // echo $json[$i]['albums']['items'][3]['images'][1]['url'];
    // echo $json[$i]['albums']['items'][3]['images'][2]['url'];

    // echo "   PICTURE 4:   ";
    // echo $json[$i]['albums']['items'][4]['images'][0]['url'];
    // echo $json[$i]['albums']['items'][4]['images'][1]['url'];
    // echo $json[$i]['albums']['items'][4]['images'][2]['url'];
    echo "\r\n";
    echo "\r\n";

    // if(($json[$i]['albums']['items'][0]['images'][0]['url'] == false) && ($json[$i]['albums']['items'][1]['images'][0]['url'] == false) && ($json[$i]['albums']['items'][2]['images'][0]['url'] == false) && ($json[$i]['albums']['items'][3]['images'][0]['url'] == false) && ($json[$i]['albums']['items'][4]['images'][0]['url'] == false)) {

    //     array_push($albumsNotFound, $json[$i]['albums']['href']);
    //     echo $json[$i]['albums']['href'];
    //     echo nl2br("\r\n");
    //     echo nl2br("\r\n");
    // }
    $albumCount++;
}
    // echo var_dump($albumsNotFound);

// $first = $json->results;
// $obj = $first[0];
// $pic = $obj->artworkUrl100;
// echo $pic;

?>