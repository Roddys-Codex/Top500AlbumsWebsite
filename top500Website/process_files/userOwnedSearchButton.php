<?php

    $ownedAlbum = $_POST['ownedAlbum'];

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?albumSearched=$ownedAlbum";

    $resource = file_get_contents($endpoint, false, stream_context_create());

    echo $resource;

?>