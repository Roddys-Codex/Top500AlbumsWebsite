<?php
    $deleteAlbumBool = false;
    $albumToBeModified = false;
    $modifyAlbumName = false;
    $modifyAlbumArtistName = false;
    $modifyAlbumYear = false;
    $modifyAlbumRank = false;
    $modifyAlbumImgUrl = false;
    $modifyAlbumPreviewUrl = false;
    $modifyAlbumCollectionViewUrl = false;
    $modifyAlbumCollectionPrice = false;
    $modifySpotifyURL = false;


    if(isset($_POST['deleteAlbumBool'])) {
        $deleteAlbumBool = $_POST['deleteAlbumBool'];
    }
    if(isset($_POST['albumToBeModified'])) {
        $albumToBeModified = $_POST['albumToBeModified'];
    }
    if(isset($_POST['modifyAlbumName'])) {
        $modifyAlbumName = $_POST['modifyAlbumName'];
    }
    if(isset($_POST['modifyAlbumArtistName'])) {
        $modifyAlbumArtistName = $_POST['modifyAlbumArtistName'];
    }
    if(isset($_POST['modifyAlbumYear'])) {
        $modifyAlbumYear = $_POST['modifyAlbumYear'];
    }
    if(isset($_POST['modifyAlbumRank'])) {
        $modifyAlbumRank = $_POST['modifyAlbumRank'];
    }
    if(isset($_POST['modifyAlbumImgUrl'])) {
        $modifyAlbumImgUrl = $_POST['modifyAlbumImgUrl'];
    }
    if(isset($_POST['modifyAlbumPreviewUrl'])) {
        $modifyAlbumPreviewUrl = $_POST['modifyAlbumPreviewUrl'];
    }
    if(isset($_POST['modifyAlbumPreviewUrl'])) {
        $modifyAlbumPreviewUrl = $_POST['modifyAlbumPreviewUrl'];
    }
    if(isset($_POST['modifyAlbumCollectionViewUrl'])) {
        $modifyAlbumCollectionViewUrl = $_POST['modifyAlbumCollectionViewUrl'];
    }
    if(isset($_POST['modifyAlbumCollectionPrice'])) {
        $modifyAlbumCollectionPrice = $_POST['modifyAlbumCollectionPrice'];
    }
    if(isset($_POST['modifyAlbumSpotifyURL'])) {
        $modifySpotifyURL = $_POST['modifyAlbumSpotifyURL'];
    }

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?modifyAlbumAdmin";

    $postdata = http_build_query(
        array(
            'deleteAlbumBool' => $deleteAlbumBool,
            'albumToBeModified' => $albumToBeModified,
            'modifyAlbumName' => $modifyAlbumName,
            'modifyAlbumArtistName' => $modifyAlbumArtistName,
            'modifyAlbumYear' => $modifyAlbumYear,
            'modifyAlbumRank' => $modifyAlbumRank,
            'modifyAlbumImgUrl' => $modifyAlbumImgUrl,
            'modifyAlbumPreviewUrl' => $modifyAlbumPreviewUrl,
            'modifyAlbumCollectionViewUrl' => $modifyAlbumCollectionViewUrl,
            'modifyAlbumCollectionPrice' => $modifyAlbumCollectionPrice,
            'modifyAlbumSpotifyURL' => $modifySpotifyURL
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