<?php 

    $host = "localhost";
    $user = "danielroddy";
    $pw = "9GqRrZcNwhNEBweG";
    $db = "top_500_albums";

    $conn = new mysqli($host, $user, $pw, $db);

    if ($conn->connect_error) {
        echo $conn->connect_error;
    } 

?>