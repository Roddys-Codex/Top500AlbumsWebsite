<?php

    $userID = $_POST['userID'];
    $albumID = $_POST['albumID'];
    $rating = $_POST['rating'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?alterRating";

    $postdata = http_build_query(
        array(
            'userID' => $userID,
            'albumID' => $albumID,
            'rating' => $rating
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
?>