<?php
    $comment = $_POST['commentUpdate'];
    $userName = $_POST['username'];
    $reviewID = $_POST['reviewID'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?updateComment";

    $postdata = http_build_query(
        array(
            'comment' => $comment,
            'userName' => $userName,
            'reviewID' => $reviewID
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