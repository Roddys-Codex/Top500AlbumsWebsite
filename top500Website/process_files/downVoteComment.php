<?php
$commentID = $_POST['comment'];
$userID = $_POST['userID'];

if(isset($_POST['removeVote'])) {
    $removeVote = $_POST['removeVote'];
}


$endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?downVote";

if(isset($_POST['removeVote'])) {
    $postdata = http_build_query(
        array(
            'userID' => $userID,
            'commentID' => $commentID,
            'removeVote' => $removeVote
        )
    );
} else {
    $postdata = http_build_query(
        array(
            'userID' => $userID,
            'commentID' => $commentID
        )
    );
}


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