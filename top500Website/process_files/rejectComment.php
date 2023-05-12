<?php

    $user_commentID_reject = $_POST['commentNumberToReject'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?rejectComment";

    $postdata = http_build_query(
        array(
            'rejectComment' => true,
            'commentIDToReject' => $user_commentID_reject
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