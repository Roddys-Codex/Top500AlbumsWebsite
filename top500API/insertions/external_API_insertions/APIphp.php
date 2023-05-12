<?php
// ALL ALBUMS
$endpointAll = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php";

$resourceAll = file_get_contents($endpointAll, false, stream_context_create());

$dataAll = json_decode($resourceAll, true);

while($dataALL != false){

    $row = array_shift($dataAll);

    $album = $row['Album'];
    $artist = $row['Artist'];

    $albumURL = urlencode($album);
    $artistURL = urlencode($artist);

    $url = "https://api.spotify.com/v1/search?q=album:$albumURL+artist:$artistURL&type=album,artist";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "Accept: application/json",
        "Authorization: Bearer BQAMtH7WTV_ETgU7iSmLA2jhzNn-M-Oa6oNM4q-_iP-7XVrXPsHplEFfn8TUgVcN0fzHyyri-jyyQRIokpIPdS_paOfitoVffU7RUtLxPVWY1HY_4vNKuxzhasQ0YY0pc90hmxZfUh12Fg",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    //for debug only!
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

    $resp = curl_exec($curl);
    curl_close($curl);
    echo var_dump($resp);
}

?>