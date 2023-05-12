<?php

    function createCookie($cookie_name, $cookie_value, $cookie_expiry = null, $domain_path = "/", $secure = null, $httpOnly = false) {

        if($cookie_expiry==null) {
            $cookie_expiry = time()+86400;
        }
        
        setcookie($cookie_name, $cookie_value, $cookie_expiry, $domain_path, $secure, $httpOnly);

    }


    // Search for user favourite albums

    // SELECT USER FAVOURITE ALBUMS ID
    function showUserFavouriteAlbums($userName, $printType) {

    $user = $userName;
    $print = $printType;

    $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?showUserFavouriteAlbums=true&userName=$user";

    $resource = file_get_contents($endpoint, false, stream_context_create());

    $data = json_decode($resource, true);
    
    if($print==="li") {
        foreach($data as $userFavRow) {

            $favAlbum = $userFavRow['album_name'];
            $artist = $userFavRow['artist_name'];

            echo "<li>{$favAlbum} by {$artist} </li>";

        }
    } else if($print==="option") {
        $i = 0;
        foreach($data as $userFavRow) {
            
            $favAlbum = $userFavRow['album_name'];
            $artist = $userFavRow['artist_name'];

            echo "<option id=\"currentFav$i\" value=\"$favAlbum\">";

            echo "{$favAlbum} by {$artist}";

            echo "</option>";

            
            

            $i++;

        }
    }

    }

    // SELECT USER OWNED ALBUMS ID
    function showUserOwnedAlbums($userName, $printType) {

        $user = $userName;
        $print = $printType;

        $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?showUserOwnedAlbums=true&userName=$user";

        $resource = file_get_contents($endpoint, false, stream_context_create());

        $data = json_decode($resource, true);

        if($print==="li") {
            foreach($data as $userOwnedRow) {

                $ownedAlbum = $userOwnedRow['album_name'];
                $artist = $userOwnedRow['artist_name'];

                echo "<li>{$ownedAlbum} by {$artist}</li>";

            }
        } else if($print==="option") {
            $i = 0;
            foreach($data as $userOwnedRow) {
                
                $ownedAlbum = $userOwnedRow['album_name'];
                $artist = $userOwnedRow['artist_name'];

                echo "<option id=\"currentOwned$i\" value=\"$ownedAlbum\">";
    
                echo "{$ownedAlbum} by {$artist}";

                echo "</option>";

                
                

                $i++;

            }
        }
        

    }

    function searchForUserBool($username) {
        
        $user = $username;

        $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?searchForUserBool=true&userName=$user";

        $resource = file_get_contents($endpoint, false, stream_context_create());

        $data = json_decode($resource, true);

        if($data != false) {
            $userNameReturn = $data['username'];
            
            if($userNameReturn) {
                return true;
            }

        } else {
            return false;
        }

    }

    function searchForEmail($userEmail) {
        
        $email = $userEmail;

        $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?searchForEmailBool=true&email=$email";

        $resource = file_get_contents($endpoint, false, stream_context_create());

        $data = json_decode($resource, true);

        if($data != false) {
            $emailNameReturn = $data['email'];
            if($emailNameReturn) {
                return true;
            }
        } else {
            return false;
        }
    }

    function validatePassword($userName, $userPassword) {
        
        $user = $userName;
        $password = $userPassword;

        $endpoint = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500API/api.php?authenticateAccount=true&userName=$user&password=$password";

        $resource = file_get_contents($endpoint, false, stream_context_create());

        $data = json_decode($resource, true);

        if($data['userValidated'] === true) {
            return true;
        } else {
            return false;
        }
    }
    
?>