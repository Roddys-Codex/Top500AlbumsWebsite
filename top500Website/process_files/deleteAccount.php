<?php
    include("../functions/functions.php");
    $userName = $_POST['usernameToDelete'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?deleteAccount";

    $postdata = http_build_query(
        array(
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