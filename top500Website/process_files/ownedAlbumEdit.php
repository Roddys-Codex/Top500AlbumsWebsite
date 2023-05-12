<?php

    $userName = $_POST['username'];
    $albumOwnedEdit = $_POST['albumOwnedForEdit'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?ownedAlbumEdit";

    $postdata = http_build_query(
        array(
            'userName' => $userName,
            'ownedAlbumEdit' => $albumOwnedEdit
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