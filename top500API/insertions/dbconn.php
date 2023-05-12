<?php 

    // $host = "droddy03.webhosting6.eeecs.qub.ac.uk";
    // $user = "droddy03";
    // $pw = "FVcx3MVTT7kqKlPJ";
    // $db = "droddy03";

    $host = "127.0.0.1";
    $user = "root";
    $pw = "root";
    $db = "album_test_db";

    $conn = new mysqli($host, $user, $pw, $db);

    if ($conn->connect_error) {
        echo $conn->connect_error;
    } 

?>