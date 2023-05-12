<?php
// ALL ALBUMS
$filename = "AlbumsNotAttainedBySpotifyEdit.csv";

$contents = fopen($filename, "r");
$api_response = array();

// ALL ALBUMS
// $endpointAll = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?albumAndArtist";

// $resourceAll = file_get_contents($endpointAll, false, stream_context_create());

// $dataAll = json_decode($resourceAll, true);

// loop to read each line from CSV file into $row array
while (($row = fgetcsv($contents)) != false) {

    $album = $row[0];
    $artist = $row[1];
    $albumID = $row[2];

    $albumURL = urlencode($album);
    $artistURL = urlencode($artist);

    $url = "https://api.spotify.com/v1/search?q=album:$albumURL+artist:$artistURL&type=album,artist,playlist";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Accept: application/json",
        "Authorization: Bearer BQApazcC9jvTDAwD3AV-4s3N3UJT6thmm3CP1tS1GPvv6o8q7tmx07jJbF3hnk6mrAmdyZ1rsisihCNpxKUTEpFqo0FcLScx9J3Bbjm1b_IZ5BrDoA-SNK4E2WNzDk58NS3luxvdypM95S06fj9oblKtZS074t0F05vuJfnSj_I_bP8HcJ1OUaDPICoWrE7FTg",
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

    } 

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?addSpotifyAlbumImageURLs";

    $postdata = http_build_query(
        array(
            'spotifyImgURL1' => $imgOne,
            'albumID' => $albumID
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

?>