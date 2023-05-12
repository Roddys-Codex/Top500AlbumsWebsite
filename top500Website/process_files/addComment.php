<?php
    $comment = $_POST['comment'];
    $userID = $_POST['userID'];
    $albumID = $_POST['albumID'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?addComment";

    $postdata = http_build_query(
        array(
            'comment' => $comment,
            'userID' => $userID,
            'albumID' => $albumID,
            'addComment' => true
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