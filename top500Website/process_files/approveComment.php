<?php

    $user_commentID_approve = $_POST['commentNumberToApprove'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?approveComment";

    $postdata = http_build_query(
        array(
            'approveComment' => true,
            'commentIDToApprove' => $user_commentID_approve
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