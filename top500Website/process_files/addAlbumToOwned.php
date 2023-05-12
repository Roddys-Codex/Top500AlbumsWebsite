<?php

    $userName = $_POST['username'];
    $ownedAlbumToAdd = $_POST['ownedAlbumToAdd'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?ownedAlbumToAdd";

    $postdata = http_build_query(
        array(
            'userName' => $userName,
            'ownedAlbumToAdd' => $ownedAlbumToAdd
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