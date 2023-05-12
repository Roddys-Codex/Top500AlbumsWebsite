<?php
    include("databaseconnection.php");

    $read = "SELECT * FROM `album`";

    $dbresult = $conn->query($read);

    if(!$dbresult) {
        echo $conn->error;
    }

    /**
     * Search iTunes with PHP.
     * 
     * @param string $searchTerm
     * @return array|boolean Associative array. Or FALSE on failure.
     */
    function search($searchTerm){
    
        //Construct our API / web services lookup URL.
        $url = 'https://itunes.apple.com/search?term=' . urlencode($searchTerm);
        
        //Use file_get_contents to get the contents of the URL.
        $result = file_get_contents($url);
        
        //If results are returned.
        if($result !== false){
            //Decode the JSON result into an associative array and return.
            return json_decode($result, true);
        }
        
        //If we reach here, something went wrong.
        return false;
    
    }

function searchTwo($searchTerm){
    //Construct our API / web services lookup URL.
    $url = 'https://itunes.apple.com/search?term=' . urlencode($searchTerm);
    //Use file_get_contents to get the contents of the URL.
    //http request:
    $result = file_get_contents($url);
    //http content:
    //echo $result;
    //jsonencode
    $json = json_decode($result);
    $first = $json->results;
    $obj = $first[0];
    $pic = $obj->artworkUrl100;
    echo $pic;
}


    while($row = $dbresult->fetch_assoc()){

            
            $albumName = $row['album_name'];
            $albumRank = $row['album_ranking'];
            $albumArtistID = (int)$row['artist_id'];

            // $readArtist = "SELECT * FROM `artist` WHERE artist_id=$albumArtistID";
            // $dbresultArtist = $conn->query($readArtist);
            // if(!$dbresultArtist) {
            //     echo $conn->error;
            // }

            print_r("ROW: ".$row);
            // print_r("DB RESULT ARTIST ::: ".$dbresultArtist);

            // $artistRow = $dbresultArtist->fetch_assoc();
            // $artistName = $artistRow['artist_name'];

            // print_r("ARTIST ROW: ".$artistRow); 
            // $searchQuery = $artistName.$albumName;

            $json = search($searchQuery);
            $first = $json->results;
            $obj = $first[0];
            $pic = $obj->artworkUrl100;

            // $insertURL = $dbresult->real_escape_string("UPDATE `album` SET `album_image_url` = {$pic} WHERE `album`.`album_name` = {$albumName}");

            // $dbInsertResult = $conn->query($insertURL);

            // if(!$dbInsertResult) {
            //     echo $conn->error;
            // }

    }
?>