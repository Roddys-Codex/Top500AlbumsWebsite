<?php

    $userName = $_POST['username'];
    $favAlbumEdit = $_POST['albumFavForEdit'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?favAlbumEdit";

    $postdata = http_build_query(
        array(
            'favAlbumEdit' => $favAlbumEdit,
            'userName' => $userName
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