<?php
    session_start();
    if(isset($_SESSION['authenticated']) && $_COOKIE['Username']) {

        $userName = $_COOKIE['Username'];
        $sessionID = session_id();
        $endpointUser = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?returnUser=true&userName=$userName";

        $resourceUser = file_get_contents($endpointUser, false, stream_context_create());

        $dataUser = json_decode($resourceUser, true);

        $sessionIDStored = $dataUser["sessionID"];

        if($sessionID != $sessionIDStored) {
            header("Location: logout.php");
            die();
        }

    } else {
        header("Location: logout.php");
        die();
    }

    $newAlbumName = $_POST['newAlbumName'];
    $newAlbumArtistName = $_POST['newAlbumArtistName'];
    $newAlbumYear = $_POST['newAlbumYear'];
    $newAlbumRank = $_POST['newAlbumRank'];
    $albumImgUrl = $_POST['albumImgUrl'];
    $albumPreviewUrl = $_POST['albumPreviewUrl'];
    $albumCollectionViewUrl = $_POST['albumCollectionViewUrl'];
    $albumCollectionPrice = $_POST['albumCollectionPrice'];
    $spotifyImage = $_POST['spotifyImage'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?addAlbumAdmin";

    $postdata = http_build_query(
        array(
            'newAlbumName' => $newAlbumName,
            'newAlbumArtistName' => $newAlbumArtistName,
            'newAlbumYear' => $newAlbumYear,
            'newAlbumRank' => $newAlbumRank,
            'albumImgUrl' => $albumImgUrl,
            'albumPreviewUrl' => $albumPreviewUrl,
            'albumCollectionViewUrl' => $albumCollectionViewUrl,
            'albumCollectionPrice' => $albumCollectionPrice,
            'spotifyImage' => $spotifyImage
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

<script>
    window.location.assign("adminPage.php");
</script>