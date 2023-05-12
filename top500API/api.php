<?php

    header("Content-Type: application/json");
    include("../top500Website/functions/functions.php");
    // include("../databaseconnection.php");

    // Return all albums
    // READ
    if ($_SERVER['REQUEST_METHOD']==='GET' && (!isset($_GET['albumSearched']) && !isset($_GET['genreFilterAll']) && !isset($_GET['userFavouriteAlbumSearch']) && !isset($_GET['userOwnedAlbumSearch']) && !isset($_GET['getRating']) && !isset($_GET['allRatings']) && !isset($_GET['userAccounts']) && !isset($_GET["yearFilter"]) && !isset($_GET['yearFilterLimit']) && !isset($_GET['returnUser']) && !isset($_GET["albumAndArtist"]) && !isset($_GET['favouriteGenres']) && !isset($_GET['findUserWithEmail'])  && !isset($_GET['returnIfUserLikedComment']) && !isset($_GET['returnIfUserDislikedComment']) && !isset($_GET['returnAllComments']) && !isset($_GET['pendingComments']) && !isset($_GET['allAlbumsLimit'])  && !isset($_GET['albumIDSearch'])  && !isset($_GET['userSpecificReview'])  && !isset($_GET['showUserFavouriteAlbums'])  && !isset($_GET['authenticateAccount']) && !isset($_GET['genreFilterLimit'])  && !isset($_GET['searchForUserBool'])  && !isset($_GET['showUserOwnedAlbums']) && !isset($_GET['searchForEmailBool']) && !isset($_GET['comments']))) {

        include ("dbconn.php");
    
        $read = "SELECT * FROM `album`";

        $dbresult = $conn->query($read);

        if(!$dbresult) {
            echo $conn->error;
        }
    
        // build a response array
        $api_response = array();
        
        while ($row = $dbresult->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

    }

    // Return all accounts
    // READ
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['userAccounts'])) {

        include ("dbconn.php");
    
        $read = "SELECT * FROM `user`";

        $dbresult = $conn->query($read);

        if(!$dbresult) {
            echo $conn->error;
        }
    
        // build a response array
        $api_response = array();
        
        while ($row = $dbresult->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

    }

    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['albumAndArtist'])) {

        include ("dbconn.php");
    
        $read = "SELECT * FROM `album`
                INNER JOIN `artist` 
                ON `album`.`artist_id`=`artist`.`artist_id`";
        

        $dbresult = $conn->query($read);

        if(!$dbresult) {
            echo $conn->error;
        }
    
        // build a response array
        $api_response = array();
        
        while ($row = $dbresult->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;
    }

    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['favouriteGenres'])) {

        include ("dbconn.php");

        

        if(isset($_GET['userName'])) {
            $userName = $_GET['userName'];

            $read = "SELECT * FROM `user_fav_genre`
                INNER JOIN `genre`
                ON `user_fav_genre`.`genre_id` = `genre`.`genre_id`
                INNER JOIN `user`
                ON `user_fav_genre`.`user_id`=`user`.`user_id`
                WHERE `user`.`username` = '$userName'";
        } else {
            $userID = $_GET['userID'];
            $read = "SELECT * FROM `user_fav_genre`
                INNER JOIN `genre`
                ON `user_fav_genre`.`genre_id` = `genre`.`genre_id`
                WHERE `user_fav_genre`.`user_id` = $userID";
        }

        $dbresult = $conn->query($read);

        if(!$dbresult) {
            echo $conn->error;
        }
    
        // build a response array
        $api_response = array();
        
        while ($row = $dbresult->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;
    }

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['addSessionToAccount'])) {

        include ("dbconn.php");

        $sessionID = $_POST['sessionID'];
        $userName = $_POST['userName'];
    
        $stmt = $conn->prepare("UPDATE `user` SET `sessionID` = ? WHERE `user`.`username` = ?;");
        $stmt->bind_param('ss', $sessionID, $userName);
        $stmt->execute();
    
        $stmt->close();
    }

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['addSpotifyAlbumImageURLs'])) {

        include ("dbconn.php");

        $spotifyImgOne = $_POST['spotifyImgURL1'];
        
        if(isset($_POST['albumName'])) {
            $albumName = $_POST['albumName'];
            $stmt = $conn->prepare("UPDATE `album` SET `album_image_spotify_url_size_one` = ? WHERE `album`.`album_name` = ?;");
            $stmt->bind_param('ss', $spotifyImgOne, $albumName);
            $stmt->execute();
            $stmt->close();
        }
        
        if(isset($_POST['albumID'])) {
            $albumID = $_POST['albumID'];
            $stmt = $conn->prepare("UPDATE `album` SET `album_image_spotify_url_size_one` = ? WHERE `album`.`album_id` = ?;");
            $stmt->bind_param('si', $spotifyImgOne, $albumID);
            $stmt->execute();
            $stmt->close();
        }
        
        

    }

    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['returnUser'])) {

        include ("dbconn.php");

        $userName = $_GET['userName'];
    
        $stmt = $conn->prepare("SELECT * FROM `user` WHERE `user`.`username` = ?;");
        $stmt->bind_param('s', $userName);
        $stmt->execute();
        
        // get the result set
        $result = $stmt->get_result();
        $api_response = array();

        $api_response = $result->fetch_assoc();
        // // build a response array
        // while($row = $result->fetch_assoc()) {
        //     array_push($api_response, $row);
        // }
        
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
    }

    // Authenticate Users
    // RETURN SPECIFIC 
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['authenticateAccount'])) {

        include ("dbconn.php");

        $userName = $_GET['userName'];
        $password = $_GET['password'];

        $stmt1 = $conn->prepare("SELECT @saltInUse := SUBSTRING(`password`, 1, 6) FROM `user` WHERE `user`.`username` = ?;");
        $stmt1->bind_param('s', $userName);
        $stmt1->execute();
        $stmt1->close();

        $stmt2 = $conn->prepare("SELECT @storedSaltedHashInUse := SUBSTRING(`password`, 7, 40) AS storedSaltedHashInUse FROM `user` WHERE `user`.`username` = ?;");
        $stmt2->bind_param('s', $userName);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $row = $result->fetch_assoc();
        if($row != false) {
            $storedSaltedHashInUse = $row['storedSaltedHashInUse'];
        } else {
            $storedSaltedHashInUse = false;
        }
        
        $stmt2->close();

        $stmt4 = $conn->prepare("SELECT @saltedHash := SHA1(CONCAT(@saltInUse, ?)) AS salted_hash_value_login");
        $stmt4->bind_param('s', $password);
        $stmt4->execute();
        $result = $stmt4->get_result();
        $row = $result->fetch_assoc();
        $loginAttemptSaltedHash = $row['salted_hash_value_login'];
        $stmt4->close();
        
        $result = array();
        
        if($storedSaltedHashInUse === $loginAttemptSaltedHash) {

            $result += array('userValidated' => true);
            
        } else {

            $result += array('userValidated' => false);
            
        }
            
        // encode the response as JSON
        $response = json_encode($result);
        
        // echo out the response
        echo $response;

    }

    // Return specific album (name)
    // RETURN SPECIFIC
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['albumSearched'])) {

        include ("dbconn.php");
        
        $album = $_GET['albumSearched'];

        $album = "%".$album."%";

        $stmt = $conn->prepare("SELECT * FROM `album` WHERE `album_name` LIKE ?;");
        $stmt->bind_param('s', $album);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();
        $api_response = array();

        // build a response array
        while($row = $result->fetch_assoc()) {
            array_push($api_response, $row);
        }
        
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
    }

    // Return albums of a genre
    // RETURN - year
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['yearFilter'])) {

        include ("dbconn.php");
        
        $yearFilter = (int)$_GET['yearFilter'];

        $stmt = $conn->prepare("SELECT * FROM album 
                                INNER JOIN `year`
                                ON album.year_id=`year`.year_id
                                WHERE `year`.`year` = ?;");

        $stmt->bind_param('i', $yearFilter);

        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // build a response array
        $api_response = array();
        
        while ($row = $result->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
    }

    // RETURN SPECIFIC - genre
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['yearFilterLimit'])) {

        include ("dbconn.php");
        
        $yearForFilter = (int)$_GET['yearFilterLimit'];
        $limit = (int)$_GET['limit'];
        $initialPage = (int)$_GET['initialPage'];

        $stmt = $conn->prepare("SELECT * FROM album 
                                INNER JOIN `year`
                                ON album.year_id=`year`.year_id
                                WHERE `year`.`year` = ? 
                                LIMIT ?, ?;");
        $stmt->bind_param('iii', $yearForFilter,$initialPage,$limit);

        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // build a response array
        $api_response = array();
        
        while ($row = $result->fetch_assoc()) {
            array_push($api_response, $row);
        }

        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;
        

        // close the statement
        $stmt->close();
    }

    // RETURN SPECIFIC - genre
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['genreFilterAll'])) {

        include ("dbconn.php");
        
        $genreForFilter = $_GET['genreFilterAll'];
        $genreForFilter = urldecode($genreForFilter);

        $stmt = $conn->prepare("SELECT * FROM album 
                                INNER JOIN album_genre
                                ON album.album_id = album_genre.album_id
                                INNER JOIN genre
                                ON album_genre.genre_id=genre.genre_id
                                WHERE genre.genre_name = ?;");

        $stmt->bind_param('s', $genreForFilter);

        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // build a response array
        $api_response = array();
        
        while ($row = $result->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
    }

    // RETURN SPECIFIC - genre
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['genreFilterLimit'])) {

        include ("dbconn.php");
        
        $genreForFilter = $_GET['genreFilter'];
        $genreForFilter = urldecode($genreForFilter);
        $limit = $_GET['limit'];
        $initialPage = $_GET['initialPage'];


        $stmt = $conn->prepare("SELECT * FROM album 
                                INNER JOIN album_genre
                                ON album.album_id = album_genre.album_id
                                INNER JOIN genre
                                ON album_genre.genre_id=genre.genre_id
                                WHERE genre.genre_name = ? 
                                LIMIT ?, ?;");

        $stmt->bind_param('sii', $genreForFilter,$initialPage,$limit);
            

        $stmt->execute();
        error_log(print_r($stmt->error, true));
        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // build a response array
        $api_response = array();
        
        while ($row = $result->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
    }

    // Return specific albumID (with albumID)
    // RETURN SPECIFIC
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['albumIDSearch'])) {

        include ("dbconn.php");
        
        $albumID = (int)$_GET['albumID'];
        
        $stmt = $conn->prepare("SELECT * FROM `album` 
                INNER JOIN `artist`
                ON `album`.`artist_id`=`artist`.`artist_id`
                WHERE album_id = ?");

        $stmt->bind_param('i', $albumID);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();
        
        $row = $result->fetch_assoc();
            
        $response = json_encode($row);
        
        echo $response;

        // close the statement
        $stmt->close();

    }

    // Return albums with limited results (pagination)
    // RETURN LIMITED RESULTS - PAGINATION
    // NOT PREPARED
    if (($_SERVER['REQUEST_METHOD']==='GET') && isset($_GET['allAlbumsLimit'])) {

        include('dbconn.php');

        $limit = $conn->real_escape_string($_GET['limit']);
        $initial_page = $conn->real_escape_string($_GET['initialPage']);
    
        $stmt = $conn->prepare("SELECT * FROM `album` LIMIT ?, ?");
        $stmt->bind_param('ii', $initial_page, $limit);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        $rowsPerPageResult = $stmt->get_result();

        // build a response array
        $api_response = array();
        
        while ($row = $rowsPerPageResult->fetch_assoc()) {
            
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        $stmt->close();
    }

    // Return user favourite album (specific)
    // RETURN SPECIFIC 'albumView.php'
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['userFavouriteAlbumSearch'])) {

        include ("dbconn.php");

        $userName = $_GET['userFavouriteAlbumSearch'];
        $albumID = $_GET['albumID'];
    
        $stmt = $conn->prepare("SELECT * FROM `user` 
                INNER JOIN `user_favourite_album`
                ON `user`.`user_id`=`user_favourite_album`.`user_id`
                INNER JOIN `album`
                ON `user_favourite_album`.`album_id`=`album`.`album_id`
                WHERE `user`.`username` = ?  && `album`.`album_id` = ?");

        $stmt->bind_param('si', $userName, $albumID);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // build a response array
        $api_response = $result->fetch_assoc();
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();

    }

    // Return user owned album (specific)
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['userOwnedAlbumSearch'])) {

        include ("dbconn.php");

        $userName = $_GET['userOwnedAlbumSearch'];
        $albumID = $_GET['albumID'];

        $stmt = $conn->prepare("SELECT * FROM `user` 
                INNER JOIN `user_owned_album`
                ON `user`.`user_id`=`user_owned_album`.`user_id`
                INNER JOIN `album`
                ON `user_owned_album`.`album_id`=`album`.`album_id`
                WHERE `user`.`username` = ?  && `album`.`album_id` = ?");

        $stmt->bind_param('si', $userName, $albumID);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();
    
        // build a response array
        $api_response = $result->fetch_assoc();
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();

    }

    // CREATE USER 
    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['addAccount'])) {

        include('dbconn.php');

        $username = $_POST['username']; 
        $email = $_POST['email'];
        $password = $_POST['password'];

        if((isset($_POST['favgenre'])) && ($_POST['favgenre'] != false)) {
            $favgenre = $_POST['favgenre'];
        } else {
            $favgenre = null;
        }

        if(isset($_POST['fname']) && ($_POST['fname'] != false)) {
            $fname = $_POST['fname'];
            $temp = $_POST['temp'];
            $fsize = $_POST['fsize'];
            $ftype = $_POST['ftype'];
            $pictureLink = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500Website/uploads/".$fname;
            $loopCount = $_POST['loopCount'];
            $userFavGenreIDArray = array();
        } else {
            $pictureLink = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500Website/uploads/default_image.jpg";
        }
        
        $sessionID = $_POST['sessionID'];

        
        if((isset($_POST['favgenre'])) && ($_POST['favgenre'] != false)) {
            for($i = 0; $i < $loopCount; $i++) {

                $stmt = $conn->prepare("SELECT `genre_id` FROM `genre` WHERE `genre_name`= ?;");
                $stmt->bind_param('s', $favgenre[$i]);
                $stmt->execute();

                if (!$stmt) {
                    echo $stmt -> error;
                }

                // get the result set
                $result = $stmt->get_result();

                $genreRow = $result->fetch_assoc();
                $userFavGenre = (int)$genreRow['genre_id'];

                // close the statement
                $stmt->close();

                $userFavGenreIDArray[] = $userFavGenre;
            }
        }

        $stmt = $conn->prepare("SELECT @salt := SUBSTRING(SHA1(RAND()), 1, 6);");
        $stmt->execute();
        $stmt->close();

        $stmt2 = $conn->prepare("SELECT @saltedHash := SHA1(CONCAT(@salt, ?)) AS salted_hash_value;");
        $stmt2->bind_param('s', $password);
        $stmt2->execute();
        $stmt2->close();

        $stmt3 = $conn->prepare("SELECT @storedSaltedHash := CONCAT(@salt,@saltedHash) AS password_to_be_stored;");
        $stmt3->execute();
        $stmt3->close();

        $stmt4 = $conn->prepare("INSERT INTO `user` (`user_id`, `username`, `email`, `password`, `profile_picture`, `user_role_id`, `sessionID`) VALUES (NULL, ?, ?, @storedSaltedHash, ?, '1', ?)");
        $stmt4->bind_param('ssss', $username, $email, $pictureLink, $sessionID);
        $stmt4->execute();
        $stmt4->close();

        $lastid = (int)$conn->insert_id;
        
        if((isset($_POST['favgenre'])) && ($_POST['favgenre'] != false)) {
            $insertArraycount = count($userFavGenreIDArray);
            for($i = 0; $i < $insertArraycount; $i++) {

                $stmt = $conn->prepare("INSERT INTO `user_fav_genre` (`user_fav_genre_id`, `user_id`, `genre_id`) VALUES (NULL, ?, ?)");
                $stmt->bind_param('ii', $lastid, $userFavGenreIDArray[$i]);
                $stmt->execute();

                if (!$stmt) {
                    echo $stmt -> error;
                }

                // get the result set
                $result = $stmt->get_result();

                // close the statement
                $stmt->close();
            }
        }
        
    }

    // SELECT SPECIFIC USER
    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['user'])) {

        include('dbconn.php');

        $userName = $_POST['username'];

        $stmt = $conn->prepare("SELECT * FROM `user` WHERE username = ?");
        $stmt->bind_param('s', $userName);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $selectRow = $result->fetch_assoc();

        // build a response array
        $api_response = $selectRow;
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
    }

    // SELECT SPECIFIC USER (EMAIL)
    if (($_SERVER['REQUEST_METHOD']==='GET') && isset($_GET['findUserWithEmail'])) {

        include('dbconn.php');

        $emailAddress = $_GET['emailAddress'];

        $stmt = $conn->prepare("SELECT * FROM `user` WHERE `user`.`email` = ?");
        $stmt->bind_param('s', $emailAddress);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $selectRow = $result->fetch_assoc();

        // build a response array
        $api_response = $selectRow;
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
    }

    // SELECT SPECIFIC USER
    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['updateSettings'])) {

        include('dbconn.php');

        $currentUserID = $_POST['currentUserID'];

        if(isset($_POST['newUserName'])) {
            $newUserName = $_POST['newUserName'];
        } else {
            $newUserName = false;
        }
        if(isset($_POST['newEmail'])) {
            $newEmail = $_POST['newEmail'];
        } else {
            $newEmail = false;
        }

        if(isset($_POST['newPassword'])) {
            $password = $_POST['newPassword'];
        } else {
            $password = false;
        }
        if(isset($_POST['newFavGenres'])) {
            $newFavGenres = $_POST['newFavGenres'];
        } else {
            $newFavGenres = false;
        }
        
        if(isset($_POST['fname'])) {
            $fname = $_POST['fname'];
            $temp = $_POST['ftemp'];
            $fsize = $_POST['fsize'];
            $ftype = $_POST['ftype'];
        } else {
            $newFavGenres = false;
            $fname = false;
            $temp = false;
            $fsize = false;
            $ftype = false;
        }

        if($password != false) {

            $stmt = $conn->prepare("SELECT @salt := SUBSTRING(SHA1(RAND()), 1, 6);");
            $stmt->execute();
            $stmt->close();

            $stmt2 = $conn->prepare("SELECT @saltedHash := SHA1(CONCAT(@salt, ?)) AS salted_hash_value;");
            $stmt2->bind_param('s', $password);
            $stmt2->execute();
            $stmt2->close();

            $stmt3 = $conn->prepare("SELECT @storedSaltedHash := CONCAT(@salt,@saltedHash) AS password_to_be_stored;");
            $stmt3->execute();
            $stmt3->close();

            $stmt4 = $conn->prepare("UPDATE `user` SET `password` = @storedSaltedHash WHERE `user`.`user_id` = ?;");
            $stmt4->bind_param('i', $currentUserID);
            $stmt4->execute();

            if (!$stmt4) {
                echo $stmt -> error;
            }

            // close the statement
            $stmt4->close();
        }
        
        if($newEmail != false) {

            $stmt = $conn->prepare("UPDATE `user` SET `email` = ? WHERE `user`.`user_id` = ?;");
            $stmt->bind_param('si', $newEmail, $currentUserID);
            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            // close the statement
            $stmt->close();
        }

        if($newUserName != false) {

            $stmt = $conn->prepare("UPDATE `user` SET `username` = ? WHERE `user`.`user_id` = ?;");
            $stmt->bind_param('si', $newUserName, $currentUserID);
            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            // close the statement
            $stmt->close();
        }

        if($fname != false) {

                $pictureLink = "http://droddy03.webhosting6.eeecs.qub.ac.uk/top500Website/uploads/".$fname;
                $stmt = $conn->prepare("UPDATE `user` SET `profile_picture` = ? WHERE `user`.`user_id` = ?;");
                $stmt->bind_param('si', $pictureLink, $currentUserID);
                error_log(print_r("STMT ERROR: ", true));
                error_log(print_r($stmt->error, true));
                $stmt->execute();
                error_log(print_r("STMT ERROR: ", true));
                error_log(print_r($stmt->error, true));
                if (!$stmt) {
                    echo $stmt -> error;
                }

                // get the result set
                $result = $stmt->get_result();

                // close the statement
                $stmt->close();
                echo "SUCCESS";
                error_log("Problem happened", true);

            // } else {
            //     echo "ERROR! Upload";
            //     echo error_get_last();
            //     error_log("Problem happened", true);
            // }

            
        } else {
            error_log("Problem happened", true);
        }

        if($newFavGenres != false) {

            $stmt = $conn->prepare("DELETE FROM `user_fav_genre` WHERE `user_fav_genre`.`user_id` = ?;");
            $stmt->bind_param('i', $currentUserID);
            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            $loopCount = count($newFavGenres);
            $userFavGenreIDArray = array();
            for($i = 0; $i < $loopCount; $i++) {

                $stmt = $conn->prepare("SELECT `genre_id` FROM `genre` WHERE `genre_name`= ?;");
                $stmt->bind_param('s', $newFavGenres[$i]);
                $stmt->execute();

                if (!$stmt) {
                    echo $stmt -> error;
                }

                // get the result set
                $result = $stmt->get_result();

                $genreRow = $result->fetch_assoc();
                $userFavGenre = (int)$genreRow['genre_id'];

                // close the statement
                $stmt->close();

                $userFavGenreIDArray[] = $userFavGenre;
            }

            $insertArraycount = count($userFavGenreIDArray);

            for($i = 0; $i < $insertArraycount; $i++) {

                $stmt = $conn->prepare("INSERT INTO `user_fav_genre` (`user_fav_genre_id`, `user_id`, `genre_id`) VALUES (NULL, ?, ?)");
                $stmt->bind_param('ii', $currentUserID, $userFavGenreIDArray[$i]);
                $stmt->execute();

                if (!$stmt) {
                    echo $stmt -> error;
                }

                // get the result set
                $result = $stmt->get_result();

                // close the statement
                $stmt->close();
            }
        }

    }

    // SELECT COMMENTS FROM SPECIFIC ALBUM
    if (($_SERVER['REQUEST_METHOD']==='GET') && isset($_GET['comments'])) {

        include('dbconn.php');

        $albumID = (int)$_GET['albumID'];

        $selectQuery = "SELECT `user`.`user_id`, `user`.`username`, `user`.`profile_picture`,`user_album_comment`.`comment_likes`, `user_album_comment`.`comment_dislikes`, `user_album_comment`.`comment_approved`, `user_album_comment`.`comment_time`, `user_album_comment`.`user_review`, `user_album_comment`.`user_album_comment_id` FROM `user_album_comment`
                                INNER JOIN `user`
                                ON `user`.`user_id` = `user_album_comment`.`user_id`
                                WHERE `user_album_comment`.`album_id`= {$albumID};";

        $selectResult = $conn->query($selectQuery);

        // get the result set
        // $result = $stmt->get_result();

        // build a response array
        $api_response = array();

        if(!$selectResult) {
            error_log(print_r($conn->error, true));
        } 
        
        while ($row = $selectResult->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        // $stmt->close();
    }


    if (($_SERVER['REQUEST_METHOD']==='GET') && isset($_GET['returnIfUserLikedComment'])) {

        include('dbconn.php');

        $commentID = (int)$_GET['commentID'];
        $userID = (int)$_GET['userID'];

        $selectQuery = "SELECT * FROM `user_album_comment_likes` 
                    WHERE `user_album_comment_likes`.`user_album_comment_id` = $commentID
                    && `user_album_comment_likes`.`liked` = 1 
                    && `user_album_comment_likes`.`user_id`= $userID;";

        $selectResult = $conn->query($selectQuery);

        $api_response = array();

        if($selectResult->num_rows == 0) {
            array_push($api_response, false);
        } else {
            array_push($api_response, true);
        }

        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

    }

    if (($_SERVER['REQUEST_METHOD']==='GET') && isset($_GET['returnIfUserDislikedComment'])) {

        include('dbconn.php');

        $commentID = (int)$_GET['commentID'];
        $userID = (int)$_GET['userID'];

        $selectQuery = "SELECT * FROM `user_album_comment_dislikes` 
                    WHERE `user_album_comment_dislikes`.`user_album_comment_id` = $commentID
                    && `user_album_comment_dislikes`.`disliked` = 1 
                    && `user_album_comment_dislikes`.`user_id`= $userID;";

        $selectResult = $conn->query($selectQuery);

        $api_response = array();

        if($selectResult->num_rows == 0) {
            array_push($api_response, false);
        } else {
            array_push($api_response, true);
        }

        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

    }

    // UPDATE RATING FOR A SPECIFIC ALBUM
    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['alterRating'])) {

        include('dbconn.php');

        $albumID = (int)$_POST['albumID'];
        $userID = (int)$_POST['userID'];
        $rating = (int)$_POST['rating'];

        $stmt = $conn->prepare("SELECT * FROM `user_rating`
                INNER JOIN `user` 
                ON `user_rating`.`user_id`=`user`.`user_id`
                INNER JOIN `album`
                ON `user_rating`.`album_id`=`album`.`album_id`
                WHERE `user`.`user_id`= ? && `album`.`album_id`= ?;");

        $stmt->bind_param('ii', $userID, $albumID);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $queryRow = $result->fetch_assoc();

        // close the statement
        $stmt->close();

        if($queryRow==false) {

            $stmt = $conn->prepare("INSERT INTO `user_rating` (`user_rating_id`, `user_id`, `user_rating`, `album_id`) VALUES (NULL, ?, ?, ?)");

            $stmt->bind_param('iii', $userID, $rating, $albumID);
            
        } else {

            $stmt = $conn->prepare("UPDATE `user_rating` SET `user_rating` = ? WHERE `user_rating`.`user_id` = ? && `user_rating`.`album_id`= ?");

            $stmt->bind_param('iii', $rating, $userID, $albumID);
        }

        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // close the statement
        $stmt->close();
    }
    
    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['upVote'])) {

        include('dbconn.php');

        $commentID = (int)$_POST['commentID'];
        $userID = (int)$_POST['userID'];

        $stmt = $conn->prepare("SELECT `user_album_comment_likes`.`liked`, `user_album_comment_likes`.`user_id`, `user_album_comment`.`user_album_comment_id` FROM              
                                        `user_album_comment_likes`
                                INNER JOIN `user` 
                                ON `user_album_comment_likes`.`user_id`=`user`.`user_id`
                                INNER JOIN `user_album_comment`
                                ON `user_album_comment_likes`.`user_album_comment_id`=`user_album_comment`.`user_album_comment_id`
                                WHERE `user`.`user_id`= ? && `user_album_comment`.`user_album_comment_id` = ?;");

        $stmt->bind_param('ii', $userID, $commentID);
        $stmt->execute();
        echo $stmt -> error;
        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $queryRow = $result->fetch_assoc();

        echo var_dump($queryRow);

        // close the statement
        $stmt->close();

        if($queryRow == false && (!isset($_POST['removeVote']))) {

            $stmt = $conn->prepare("INSERT INTO `user_album_comment_likes` (`user_album_comment_likes`, `user_id`, `user_album_comment_id`, `liked`) VALUES (NULL, ?, ?, 1)");

            $stmt->bind_param('ii', $userID, $commentID);

            $stmt->execute();

            echo $stmt -> error;

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            // close the statement
            $stmt->close();

            $stmt = $conn->prepare("SELECT * FROM `user_album_comment_likes` WHERE `user_album_comment_likes`.`user_album_comment_id` = ? AND `user_album_comment_likes`.`liked`=1;");

            $stmt->bind_param('i', $commentID);

            $stmt->execute();
            echo $stmt -> error;
            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            $api_response = array();

            while($row = $result->fetch_assoc()) {
                array_push($api_response, $row);
            }

            $numOfLikes = count($api_response);

            // close the statement
            $stmt->close();

            $stmt = $conn->prepare("UPDATE `user_album_comment` SET `comment_likes` = ? WHERE `user_album_comment`.`user_album_comment_id` = ?;");

            $stmt->bind_param('ii', $numOfLikes, $commentID);

            $stmt->execute();

            echo $stmt -> error;

            $stmt->close();
            
        } else if(isset($queryRow) && !isset($_POST['removeVote'])) {

            $stmt = $conn->prepare("UPDATE `user_album_comment_likes` SET `liked` = '1' WHERE `user_album_comment_likes`.`user_id` = ? AND `user_album_comment_likes`.`user_album_comment_id`= ?;");

            $stmt->bind_param('ii', $userID, $commentID);

            $stmt->execute();
            echo $stmt -> error;
            $stmt->close();

            $stmt = $conn->prepare("SELECT * FROM `user_album_comment_likes` WHERE `user_album_comment_likes`.`user_album_comment_id` = ? AND `user_album_comment_likes`.`liked`=1;");

            $stmt->bind_param('i', $commentID);

            $stmt->execute();

            echo $stmt -> error;

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            $api_response = array();

            while($row = $result->fetch_assoc()) {
                array_push($api_response, $row);
            }

            $numOfLikes = count($api_response);

            // close the statement
            $stmt->close();

            $stmt = $conn->prepare("UPDATE `user_album_comment` SET `comment_likes` = ? WHERE `user_album_comment`.`user_album_comment_id` = ?;");

            $stmt->bind_param('ii', $numOfLikes, $commentID);

            $stmt->execute();

            echo $stmt -> error;

            $stmt->close();
        
        }  else if(isset($_POST['removeVote'])) {

            $stmt = $conn->prepare("UPDATE `user_album_comment_likes` SET `liked` = '0' WHERE `user_album_comment_likes`.`user_id` = ? AND `user_album_comment_likes`.`user_album_comment_id`= ?;");

            $stmt->bind_param('ii', $userID, $commentID);

            $stmt->execute();
            echo $stmt -> error;
            $stmt->close();

            $stmt = $conn->prepare("SELECT * FROM `user_album_comment_likes` WHERE `user_album_comment_likes`.`user_album_comment_id` = ? AND `user_album_comment_likes`.`liked`=1;");

            $stmt->bind_param('i', $commentID);

            $stmt->execute();

            echo $stmt -> error;

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            $api_response = array();

            while($row = $result->fetch_assoc()) {
                array_push($api_response, $row);
            }

            $numOfLikes = count($api_response);

            // close the statement
            $stmt->close();

            $stmt = $conn->prepare("UPDATE `user_album_comment` SET `comment_likes` = ? WHERE `user_album_comment`.`user_album_comment_id` = ?;");

            $stmt->bind_param('ii', $numOfLikes, $commentID);

            $stmt->execute();

            echo $stmt -> error;

            $stmt->close();
        }
        

        
    }

    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['downVote'])) {

        include('dbconn.php');

        $commentID = (int)$_POST['commentID'];
        $userID = (int)$_POST['userID'];

        $stmt = $conn->prepare("SELECT `user_album_comment_dislikes`.`disliked`, `user_album_comment_dislikes`.`user_id`, `user_album_comment`.`user_album_comment_id` FROM              
                                `user_album_comment_dislikes`
                                INNER JOIN `user` 
                                ON `user_album_comment_dislikes`.`user_id`=`user`.`user_id`
                                INNER JOIN `user_album_comment`
                                ON `user_album_comment_dislikes`.`user_album_comment_id`=`user_album_comment`.`user_album_comment_id`
                                WHERE `user`.`user_id`= ? && `user_album_comment`.`user_album_comment_id` = ?;");

        $stmt->bind_param('ii', $userID, $commentID);
        $stmt->execute();
        echo $stmt -> error;
        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $queryRow = $result->fetch_assoc();

        echo var_dump($queryRow);

        // close the statement
        $stmt->close();

        if($queryRow == false && (!isset($_POST['removeVote']))) {

            $stmt = $conn->prepare("INSERT INTO `user_album_comment_dislikes` (`user_album_comment_dislikes`, `user_id`, `user_album_comment_id`, `disliked`) VALUES (NULL, ?, ?, 1)");

            $stmt->bind_param('ii', $userID, $commentID);

            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            // close the statement
            $stmt->close();

            $stmt = $conn->prepare("SELECT * FROM `user_album_comment_dislikes` WHERE `user_album_comment_dislikes`.`user_album_comment_id` = ? AND `user_album_comment_dislikes`.`disliked`=1;");

            $stmt->bind_param('i', $commentID);

            $stmt->execute();
            echo $stmt -> error;
            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            $api_response = array();

            while($row = $result->fetch_assoc()) {
                array_push($api_response, $row);
            }

            $numOfDislikes = count($api_response);

            // close the statement
            $stmt->close();

            $stmt = $conn->prepare("UPDATE `user_album_comment` SET `comment_dislikes` = ? WHERE `user_album_comment`.`user_album_comment_id` = ?;");

            $stmt->bind_param('ii', $numOfDislikes, $commentID);

            $stmt->execute();

            echo $stmt -> error;

            $stmt->close();
            
        } else if($queryRow['disliked']==0 && !isset($_POST['removeVote'])) {
        
            $stmt = $conn->prepare("UPDATE `user_album_comment_dislikes` SET `disliked` = '1' WHERE `user_album_comment_dislikes`.`user_id` = ? AND `user_album_comment_dislikes`.`user_album_comment_id`= ?;");

            $stmt->bind_param('ii', $userID, $commentID);

            $stmt->execute();
            echo $stmt -> error;
            $stmt->close();

            $stmt = $conn->prepare("SELECT * FROM `user_album_comment_dislikes` WHERE `user_album_comment_dislikes`.`user_album_comment_id` = ? AND `user_album_comment_dislikes`.`disliked`=1;");

            $stmt->bind_param('i', $commentID);

            $stmt->execute();

            echo $stmt -> error;

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            $api_response = array();

            while($row = $result->fetch_assoc()) {
                array_push($api_response, $row);
            }

            $numOfDislikes = count($api_response);

            // close the statement
            $stmt->close();

            $stmt = $conn->prepare("UPDATE `user_album_comment` SET `comment_dislikes` = ? WHERE `user_album_comment`.`user_album_comment_id` = ?;");

            $stmt->bind_param('ii', $numOfDislikes, $commentID);

            $stmt->execute();

            echo $stmt -> error;

            $stmt->close();
        
        
        }  else if(isset($_POST['removeVote'])) {

            $stmt = $conn->prepare("UPDATE `user_album_comment_dislikes` SET `disliked` = '0' WHERE `user_album_comment_dislikes`.`user_id` = ? AND `user_album_comment_dislikes`.`user_album_comment_id`= ?;");

            $stmt->bind_param('ii', $userID, $commentID);

            $stmt->execute();
            echo $stmt -> error;
            $stmt->close();

            $stmt = $conn->prepare("SELECT * FROM `user_album_comment_dislikes` WHERE `user_album_comment_dislikes`.`user_album_comment_id` = ? AND `user_album_comment_dislikes`.`disliked`=1;");

            $stmt->bind_param('i', $commentID);

            $stmt->execute();

            echo $stmt -> error;

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();
            $rowCount = $result->num_rows;

            $api_response = array();


            while($row = $result->fetch_assoc()) {
                array_push($api_response, $row);
            }

            $numOfDislikes = count($api_response);
            // close the statement
            $stmt->close();

            

            if($rowCount == 0) {
                $stmt = $conn->prepare("UPDATE `user_album_comment` SET `comment_dislikes` = '0' WHERE `user_album_comment`.`user_album_comment_id` = ?;");
                $stmt->bind_param('i', $commentID);
            } else {
                $stmt = $conn->prepare("UPDATE `user_album_comment` SET `comment_dislikes` = ? WHERE `user_album_comment`.`user_album_comment_id` = ?;");

                $stmt->bind_param('ii', $numOfDislikes, $commentID);
            }

            
            $stmt->execute();

            $stmt->close();
        }
        

        
    }

    // GET RATING FOR A SPECIFIC USER RATING FOR AN ALBUM 
    if (($_SERVER['REQUEST_METHOD']==='GET') && isset($_GET['getRating'])) {

        include('dbconn.php');

        $albumID = (int)$_GET['albumID'];
        $userName = $_GET['userName'];

        $stmt = $conn->prepare("SELECT * FROM `user_rating`
                INNER JOIN `user` 
                ON `user_rating`.`user_id`=`user`.`user_id`
                INNER JOIN `album`
                ON `user_rating`.`album_id`=`album`.`album_id`
                WHERE `user`.`username`= ? && `album`.`album_id`= ?;");

        $stmt->bind_param('si', $userName, $albumID);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $queryRow = $result->fetch_assoc();

        // encode the response as JSON
        $response = json_encode($queryRow);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
    }

    // RETURN ALL RATINGS
    if (($_SERVER['REQUEST_METHOD']==='GET') && isset($_GET['allRatings'])) {

        include('dbconn.php');

        $albumID = (int)$_GET['albumID'];

        $stmt = $conn->prepare("SELECT `user_rating` FROM `user_rating`
                INNER JOIN `album`
                ON `user_rating`.`album_id`=`album`.`album_id`
                WHERE `album`.`album_id`= ?;");

        $stmt->bind_param('i', $albumID);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // build a response array
        $api_response = array();
        
        while ($row = $result->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();

    }

    // SELECT PENDING COMMENTS FOR APPROVAL/REJECTION
    if (($_SERVER['REQUEST_METHOD']==='GET') && isset($_GET['pendingComments'])) {

        include('dbconn.php');

        // ALBUM COMMENTS QUERY
        $selectQuery = "SELECT * FROM `user_album_comment`
                        INNER JOIN `user`
                        ON `user_album_comment`.`user_id`=`user`.`user_id`
                        INNER JOIN `album`
                        ON `user_album_comment`.`album_id`=`album`.`album_id`
                        WHERE `user_album_comment`.`comment_approved`=0;";

        $selectResult = $conn->query($selectQuery);

        if(!$selectResult) {
            echo $conn->error;
        } 

        // build a response array
        $api_response = array();
        
        while ($row = $selectResult->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;
    }

    // SELECT ALL COMMENTS 
    if (($_SERVER['REQUEST_METHOD']==='GET') && isset($_GET['returnAllComments'])) {

        include('dbconn.php');

        // ALBUM COMMENTS QUERY
        $selectQuery = "SELECT * FROM `user_album_comment`
                        INNER JOIN `user`
                        ON `user_album_comment`.`user_id` = `user`.`user_id`
                        INNER JOIN album 
                        ON `user_album_comment`.`album_id` = `album`.`album_id`";

        $selectResult = $conn->query($selectQuery);

        if(!$selectResult) {
            echo $conn->error;
        } 

        // build a response array
        $api_response = array();
        
        while ($row = $selectResult->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;
    }
    
    // SELECT PENDING COMMENTS FOR APPROVAL/REJECTION
    if (($_SERVER['REQUEST_METHOD']==='GET') && isset($_GET['userSpecificReview'])) {

        include('dbconn.php');

        $userName = $_GET['username'];

        $stmt = $conn->prepare("SELECT * FROM `user_album_comment`
                        INNER JOIN `user`
                        ON `user_album_comment`.`user_id`=`user`.`user_id`
                        INNER JOIN `album`
                        ON `user_album_comment`.`album_id`=`album`.`album_id`
                        WHERE `user`.`username`= ?;");
        $stmt->bind_param('s', $userName);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // build a response array
        $api_response = array();
        
        while ($row = $result->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
    }

    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['bioUpdate'])) {

        include('dbconn.php');

        $userName = $_POST['userName'];
        $biography = $_POST['bioUpdate'];

        $stmt = $conn->prepare("UPDATE `user` SET `biography` = ? WHERE `user`.`username` = ?");
        $stmt->bind_param('ss', $biography, $userName);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // close the statement
        $stmt->close();
        
    }

    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['favAlbumSearch'])) {

        include('dbconn.php');
            
        $favAlbumSearch = $_POST['favAlbumSearch'];
        $favAlbumSearch = "%".$favAlbumSearch."%";
        
        $stmt = $conn->prepare("SELECT `album_name` FROM `album` WHERE `album`.`album_name` LIKE ?");
        $stmt->bind_param('s', $favAlbumSearch);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // build a response array
        $api_response = array();

        while($rowSearch = $result->fetch_assoc()) {
            array_push($api_response, $rowSearch);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
        
    }

    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['favAlbumAdd'])) {

        include('dbconn.php');
        
        $userName = $_POST['userName'];
        $favAlbumReturn = $_POST['favAlbumAdd'];
            
        if(isset($favAlbumReturn)) {

        $stmt = $conn->prepare("SELECT `user_id` FROM `user` WHERE `user`.`username` = ?");
        $stmt->bind_param('s', $userName);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $rowUserIDSearch = $result->fetch_assoc();

        $userID = (int)$rowUserIDSearch['user_id'];

        // close the statement
        $stmt->close();

        $stmt = $conn->prepare("SELECT `album_id` FROM `album` WHERE `album`.`album_name` = ?");
        $stmt->bind_param('s', $favAlbumReturn);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $rowIDSearch = $result->fetch_assoc();

        $albumID = (int)$rowIDSearch['album_id'];

        // close the statement
        $stmt->close();

        // FAV ALBUM CHECK QUERY
        $stmt = $conn->prepare("SELECT * FROM `user_favourite_album` WHERE `user_id` = ? AND `album_id` = ?");
        $stmt->bind_param('ii', $userID, $albumID);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $favCheckResult = $stmt->get_result();

        // close the statement
        $stmt->close();
        
        // INSERT IF
        if($favCheckResult->num_rows == 0) {

            $stmt = $conn->prepare("INSERT INTO `user_favourite_album` (`user_favourite_album_id`, `user_id`, `album_id`) VALUES (NULL, ?, ?)");
            $stmt->bind_param('ii', $userID, $albumID);
            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            // close the statement
            $stmt->close();
        }

        $favAlbumReturn = null;

    }
        
    }

    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['favAlbumEdit'])) {

        include('dbconn.php');

        $favAlbumEdit = $_POST['favAlbumEdit'];
        $userName = $_POST['userName'];

        if(isset($favAlbumEdit)) {

            $stmt = $conn->prepare("SELECT * FROM `album` WHERE `album`.`album_name`= ?");
            $stmt->bind_param('s', $favAlbumEdit);
            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            $albumEditRow = $result->fetch_assoc();

            $albumEditID = (int)$albumEditRow['album_id'];

            // close the statement
            $stmt->close();

            $stmt = $conn->prepare("SELECT * FROM `user` WHERE `user`.`username`= ?");
            $stmt->bind_param('s', $userName);
            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            $userSelectionRow = $result->fetch_assoc();

            $userID = (int)$userSelectionRow['user_id'];

            // close the statement
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM `user_favourite_album` WHERE `user_favourite_album`.`album_id`= ? && `user_favourite_album`.`user_id`= ?;");
            $stmt->bind_param('ii', $albumEditID, $userID);
            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            // close the statement
            $stmt->close();
            
            $favAlbumEdit = null;
        }
    }

    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['ownedAlbumSearch'])) {

        include('dbconn.php');
        
        $ownedAlbumSearch = $_POST['ownedAlbumSearch'];
        $ownedAlbumSearch = "%".$ownedAlbumSearch."%";

        // SEARCH FOR OWNED ALBUM
        $stmt = $conn->prepare("SELECT `album_name` FROM `album` WHERE `album`.`album_name` LIKE ?");
        $stmt->bind_param('s', $ownedAlbumSearch);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // build a response array
        $api_response = array();
        
        while ($rowOwnedSearch = $result->fetch_assoc()) {
            array_push($api_response, $rowOwnedSearch);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();
        
    }

    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['ownedAlbumToAdd'])) {

        include('dbconn.php');
        
        $ownedAlbumSearch = $_POST['ownedAlbumToAdd'];
        $userName = $_POST['userName'];

        if(isset($ownedAlbumSearch)) {
            // USER ID QUERY

            $stmt = $conn->prepare("SELECT `user_id` FROM `user` WHERE `user`.`username` = ?");
            $stmt->bind_param('s', $userName);
            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            $rowUserIDSearch = $result->fetch_assoc();

            $userID = (int)$rowUserIDSearch['user_id'];

            // close the statement
            $stmt->close();

            // ALBUM ID QUERY
            $stmt = $conn->prepare("SELECT `album_id` FROM `album` WHERE `album`.`album_name` = ?");
            $stmt->bind_param('s', $ownedAlbumSearch);
            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $result = $stmt->get_result();

            $rowIDSearch = $result->fetch_assoc();

            $albumID = (int)$rowIDSearch['album_id'];

            // close the statement
            $stmt->close();

            // OWNED ALBUM CHECK QUERY

            $stmt = $conn->prepare("SELECT * FROM `user_owned_album` WHERE `user_id` = ? AND `album_id` = ?");
            $stmt->bind_param('ii', $userID, $albumID);
            $stmt->execute();

            if (!$stmt) {
                echo $stmt -> error;
            }

            // get the result set
            $ownedCheckResult = $stmt->get_result();

            // close the statement
            $stmt->close();
            
            // INSERT IF
            if($ownedCheckResult->num_rows == 0) {

                // UPDATE USER FAVOURITE
                $stmt = $conn->prepare("INSERT INTO `user_owned_album` (`user_owned_album_id`, `user_id`, `album_id`) VALUES (NULL, ?, ?)");
                $stmt->bind_param('ii', $userID, $albumID);
                $stmt->execute();

                if (!$stmt) {
                    echo $stmt -> error;
                }

                // get the result set
                $result = $stmt->get_result();

                // close the statement
                $stmt->close();
            }

            $ownedAlbumSearch = null;

        }
        
    }

    if (($_SERVER['REQUEST_METHOD']==='POST') && isset($_GET['ownedAlbumEdit'])) {

        include('dbconn.php');

        $ownedAlbumForEdit = $_POST['ownedAlbumEdit'];
        $userName = $_POST['userName'];

        if(isset($ownedAlbumForEdit)) {

        $stmt = $conn->prepare("SELECT * FROM `album` WHERE `album`.`album_name`= ?");
        $stmt->bind_param('s', $ownedAlbumForEdit);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $albumOwnedEditRow = $result->fetch_assoc();

        $albumOwnedEditID = (int)$albumOwnedEditRow['album_id'];

         // close the statement
        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM `user` WHERE `user`.`username`= ?");
        $stmt->bind_param('s', $userName);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $userSelectionRow = $result->fetch_assoc();

        $userID = (int)$userSelectionRow['user_id'];

        // close the statement
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `user_owned_album` WHERE `user_owned_album`.`album_id`= ? && `user_owned_album`.`user_id`= ?;");
        $stmt->bind_param('ii', $albumOwnedEditID, $userID);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // close the statement
        $stmt->close();

        $ownedAlbumForEdit = null;

    }
    }

    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['showUserFavouriteAlbums'])) {
        include ("dbconn.php");

        $userName = $_GET['userName'];
    
        $stmt = $conn->prepare("SELECT album_name, artist_name 
                FROM user_favourite_album 
                INNER JOIN user 
                ON user_favourite_album.user_id=user.user_id 
                INNER JOIN album 
                ON user_favourite_album.album_id=album.album_id 
                INNER JOIN artist 
                ON album.artist_id=artist.artist_id 
                WHERE user.username = ?");
        $stmt->bind_param('s', $userName);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();
    
        // build a response array
        $api_response = array();
        
        while ($row = $result->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();

    }
    
    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['showUserOwnedAlbums'])) {
        include ("dbconn.php");

        $userName = $_GET['userName'];

        $stmt = $conn->prepare("SELECT album_name, artist_name 
                FROM user_owned_album 
                INNER JOIN user 
                ON user_owned_album.user_id=user.user_id 
                INNER JOIN album 
                ON user_owned_album.album_id=album.album_id 
                INNER JOIN artist 
                ON album.artist_id=artist.artist_id 
                WHERE user.username = ?");
        $stmt->bind_param('s', $userName);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // build a response array
        $api_response = array();
        
        while ($row = $result->fetch_assoc()) {
            array_push($api_response, $row);
        }
            
        // encode the response as JSON
        $response = json_encode($api_response);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();

    }

    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['searchForUserBool'])) {

        include ("dbconn.php");

        $userName = $_GET['userName'];
    
        $stmt = $conn->prepare("SELECT * FROM `user` WHERE username = ?;");
        $stmt->bind_param('s', $userName);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $userSearchRow = $result->fetch_assoc();
        
        // encode the response as JSON
        $response = json_encode($userSearchRow);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();

    }

    if ($_SERVER['REQUEST_METHOD']==='GET' && isset($_GET['searchForEmailBool'])) {

        include ("dbconn.php");

        $email = $_GET['email'];

        $stmt = $conn->prepare("SELECT * FROM `user` WHERE email = ?;");
        $stmt->bind_param('s', $email);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        $userEmailRow = $result->fetch_assoc();
        
        // encode the response as JSON
        $response = json_encode($userEmailRow);
        
        // echo out the response
        echo $response;

        // close the statement
        $stmt->close();

    }

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['addComment'])) {

        include ("dbconn.php");

        $userID = (int)$_POST['userID'];
        $comment = $_POST['comment'];
        $albumID = (int)$_POST['albumID'];
    
        $stmt = $conn->prepare("INSERT INTO `user_album_comment` (`user_album_comment_id`, `user_id`, `user_review`, `comment_approved`, `comment_likes`,`comment_dislikes`, `comment_time`, `album_id`) VALUES (NULL, ?, ?, '0', '0', '0', CURRENT_TIMESTAMP, ?);");

        $stmt->bind_param('isi', $userID, $comment, $albumID);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // close the statement
        $stmt->close();

    }

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['addAlbumAdmin'])) {

        include ("dbconn.php");
        
        $newAlbumName = $_POST['newAlbumName'];
        $newAlbumArtistName = $_POST['newAlbumArtistName'];
        $newAlbumYear = $_POST['newAlbumYear'];
        $newAlbumRank = $_POST['newAlbumRank'];
        $albumImgUrl = $_POST['albumImgUrl'];
        $albumPreviewUrl = $_POST['albumPreviewUrl'];
        $albumCollectionViewUrl = $_POST['albumCollectionViewUrl'];
        $albumCollectionPrice = $_POST['albumCollectionPrice'];
        $spotifyImage = $_POST['spotifyImage'];
    
        $stmt = $conn->prepare("SELECT * FROM `year` WHERE year= ?;");
        $stmt->bind_param('i', $newAlbumYear);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();
        
        if(($result->num_rows == 0) && ($newAlbumYear != false)) {

            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO `year` (`year_id`, `year`, `no_albums_released`, `artist_music_releases`) VALUES (NULL, ?, NULL, NULL)");
            $stmt->bind_param('i', $newAlbumYear);
            $stmt->execute();
            $yearID = (int)$conn->insert_id;
            $stmt->close();
            

        } else if($newAlbumYear != false) {

            $yearRow = $result->fetch_assoc();
            $yearID = (int)$yearRow['year_id'];
            $stmt->close();
        }

        $stmt = $conn->prepare("SELECT * FROM `artist` WHERE artist.artist_name= ?");
        $stmt->bind_param('s', $newAlbumArtistName);
        $stmt->execute();

        if (!$stmt) {
        echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        if(($result->num_rows == 0) && ($newAlbumArtistName != false)) {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO `artist` (`artist_id`, `artist_name`, `no_albums_on_list`, `highest_rank_on_list`, `highest_album_on_list`, `artist_view_url`) VALUES (NULL, ?, 'NULL', 'NULL', 'NULL', 'NULL')");
            $stmt->bind_param('s', $newAlbumArtistName);
            $stmt->execute();
            $artistID = (int)$conn->insert_id;
            $stmt->close();
            
        } else if($newAlbumArtistName != false) {

            $artistRow = $result->fetch_assoc();
            $artistID = $artistRow['artist_id'];
            $stmt->close();
        }
        
        $stmt = $conn->prepare("INSERT INTO `album` (`album_id`, `album_name`, `album_ranking`, `year_id`, `artist_id`, `album_image_url`, `album_preview_url`, `album_collection_view_url`, `album_collection_price`, `album_image_spotify_url_size_one`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param('siiisssss', $newAlbumName, $newAlbumRank, $yearID, $artistID, $albumImgUrl, $albumPreviewUrl, $albumCollectionViewUrl, $albumCollectionPrice, $spotifyImage);
        $stmt->execute();

        if (!$stmt) {
            echo $stmt -> error;
        }

        // get the result set
        $result = $stmt->get_result();

        // close the statement
        $stmt->close();

    }
    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['modifyAlbumAdmin'])) {

        include ("dbconn.php");

        $deleteAlbumBool = $_POST['deleteAlbumBool'];
        $albumToBeModifiedID = (int)$_POST['albumToBeModified'];
        $modifyAlbumName = $_POST['modifyAlbumName'];
        $modifyAlbumArtistName = $_POST['modifyAlbumArtistName'];
        $modifyAlbumYear = $_POST['modifyAlbumYear'];
        $modifyAlbumRank = $_POST['modifyAlbumRank'];
        $modifyAlbumImgUrl = $_POST['modifyAlbumImgUrl'];
        $modifyAlbumPreviewUrl = $_POST['modifyAlbumPreviewUrl'];
        $modifyAlbumCollectionViewUrl = $_POST['modifyAlbumCollectionViewUrl'];
        $modifyAlbumCollectionPrice = $_POST['modifyAlbumCollectionPrice'];
        $modifyAlbumSpotifyURL = $_POST['modifyAlbumSpotifyURL'];
    
        $stmt = $conn->prepare("SELECT * FROM `year` WHERE year= ?;");
        $stmt->bind_param('i', $modifyAlbumYear);
        $stmt->execute();

        $result = $stmt->get_result();
        
        if(($result->num_rows == 0) && ($modifyAlbumYear != false)) {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO `year` (`year_id`, `year`, `no_albums_released`, `artist_music_releases`) VALUES (NULL, ?, NULL, NULL)");
            $stmt->bind_param('i', $modifyAlbumYear);
            $stmt->execute();
            $yearID = (int)$conn->insert_id;
            $stmt->close();

        } else if($modifyAlbumYear != false) {

            $yearRow = $result->fetch_assoc();
            $yearID = (int)$yearRow['year_id'];
            $stmt->close();
        }

        $stmt = $conn->prepare("SELECT * FROM `artist` WHERE artist.artist_name= ?");
        $stmt->bind_param('s', $modifyAlbumArtistName);
        $stmt->execute();

        $result = $stmt->get_result();

        if(($result->num_rows == 0) && ($modifyAlbumArtistName != false)) {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO `artist` (`artist_id`, `artist_name`, `no_albums_on_list`, `highest_rank_on_list`, `highest_album_on_list`, `artist_view_url`) VALUES (NULL, ?, 'NULL', 'NULL', 'NULL', 'NULL')");
            $stmt->bind_param('s', $modifyAlbumArtistName);
            $stmt->execute();
            $artistID = (int)$conn->insert_id;
            $stmt->close();
            
            
        } else if($modifyAlbumArtistName != false) {

            $artistRow = $result->fetch_assoc();
            $artistID = $artistRow['artist_id'];
            $stmt->close();
        }

        // UPDATES 
        if($modifyAlbumName != false) {

            $stmt = $conn->prepare("UPDATE `album` SET `album_name` = ? WHERE `album`.`album_id` = ?;");
            $stmt->bind_param('si', $modifyAlbumName, $albumToBeModifiedID);
            $stmt->execute();
            $stmt->close();
        }

        if($artistID != false) {
            $stmt = $conn->prepare("UPDATE `album` SET `artist_id` = ? WHERE `album`.`album_id` = ?;");
            $stmt->bind_param('ii', $artistID, $albumToBeModifiedID);
            $stmt->execute();
            $stmt->close();
        }

        if($yearID != false) {

            $stmt = $conn->prepare("UPDATE `album` SET `year_id` = ? WHERE `album`.`album_id` = ?;");
            $stmt->bind_param('ii', $yearID, $albumToBeModifiedID);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyAlbumRank != false) {

            $stmt = $conn->prepare("UPDATE `album` SET `album_ranking` = ? WHERE `album`.`album_id` = ?;");
            $stmt->bind_param('ii', $modifyAlbumRank, $albumToBeModifiedID);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyAlbumImgUrl != false) {

            $stmt = $conn->prepare("UPDATE `album` SET `album_image_url` = ? WHERE `album`.`album_id` = ?;");
            $stmt->bind_param('si', $modifyAlbumImgUrl, $albumToBeModifiedID);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyAlbumPreviewUrl != false) {

            $stmt = $conn->prepare("UPDATE `album` SET `album_preview_url` = ? WHERE `album`.`album_id` = ?;");
            $stmt->bind_param('si', $modifyAlbumPreviewUrl, $albumToBeModifiedID);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyAlbumCollectionViewUrl != false) {

            $stmt = $conn->prepare("UPDATE `album` SET `album_collection_view_url` = ? WHERE `album`.`album_id` = ?;");
            $stmt->bind_param('si', $modifyAlbumCollectionViewUrl, $albumToBeModifiedID);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyAlbumCollectionPrice != false) {

            $stmt = $conn->prepare("UPDATE `album` SET `album_collection_price` = ? WHERE `album`.`album_id` = ?;");
            $stmt->bind_param('si', $modifyAlbumCollectionPrice, $albumToBeModifiedID);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyAlbumSpotifyURL != false) {

            $stmt = $conn->prepare("UPDATE `album` SET `album_image_spotify_url_size_one` = ? WHERE `album`.`album_id` = ?;");
            $stmt->bind_param('si', $modifyAlbumSpotifyURL, $albumToBeModifiedID);
            $stmt->execute();
            $stmt->close();
        }

        if($deleteAlbumBool != false) {

            $stmt = $conn->prepare("DELETE FROM `album` WHERE `album`.`album_id` = ?");
            $stmt->bind_param('i', $albumToBeModifiedID);
            $stmt->execute();
            $stmt->close();
        }

    }

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['modifyAccountAdmin'])) {

        include ("dbconn.php");

        $userToBeModified = false;
        $modifyUsername = false;
        $modifyEmail = false;
        $modifyPassword = false;
        $modifyProfilePic = false;
        $modifyBio = false;
        $modifyUserRole = false;
        $deleteAccountBool = false;

        if(isset($_POST['userToBeModified'])) {
        $userToBeModified = $_POST['userToBeModified'];
        }
        if(isset($_POST['modifyUsername'])) {
            $modifyUsername = $_POST['modifyUsername'];
        }
        if(isset($_POST['modifyEmail'])) {
            $modifyEmail = $_POST['modifyEmail'];
        }
        if(isset($_POST['modifyPassword'])) {
            $modifyPassword = $_POST['modifyPassword'];
        }
        if(isset($_POST['modifyProfilePic'])) {
            $modifyProfilePic = $_POST['modifyProfilePic'];
        }
        if(isset($_POST['modifyBio'])) {
            $modifyBio = $_POST['modifyBio'];
        }
        if(isset($_POST['modifyUserRole'])) {
            $modifyUserRole = $_POST['modifyUserRole'];
        }
        if(isset($_POST['deleteAccountBool'])) {
            $deleteAccountBool = $_POST['deleteAccountBool'];
        }

        if($deleteAccountBool != false) {

            $stmt = $conn->prepare("DELETE FROM `user_owned_album` WHERE `user_owned_album`.`user_id` = ?;");
            $stmt->bind_param('i', $userToBeModified);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM `user_favourite_album` WHERE `user_favourite_album`.`user_id` = ?;");
            $stmt->bind_param('i', $userToBeModified);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM `user_album_comment` WHERE `user_album_comment`.`user_id` = ?;");
            $stmt->bind_param('i', $userToBeModified);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM `user_rating` WHERE `user_rating`.`user_id` = ?;");
            $stmt->bind_param('i', $userToBeModified);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM `user_fav_genre` WHERE `user_fav_genre`.`user_id` = ?;");
            $stmt->bind_param('i', $userToBeModified);
            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("DELETE FROM `user` WHERE `user`.`user_id` = ?;");
            $stmt->bind_param('i', $userToBeModified);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyUsername != false) {

            $stmt = $conn->prepare("UPDATE `user` SET `username` = ? WHERE `user`.`user_id` = ?;");
            $stmt->bind_param('si', $modifyUsername, $userToBeModified);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyEmail != false) {

            $stmt = $conn->prepare("UPDATE `user` SET `email` = ? WHERE `user`.`user_id` = ?;");
            $stmt->bind_param('si', $modifyEmail, $userToBeModified);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyPassword != false) {

            $stmt = $conn->prepare("SELECT @salt := SUBSTRING(SHA1(RAND()), 1, 6);");
            $stmt->execute();
            $stmt->close();

            $stmt2 = $conn->prepare("SELECT @saltedHash := SHA1(CONCAT(@salt, ?)) AS salted_hash_value;");
            $stmt2->bind_param('s', $modifyPassword);
            $stmt2->execute();
            $stmt2->close();

            $stmt3 = $conn->prepare("SELECT @storedSaltedHash := CONCAT(@salt,@saltedHash) AS password_to_be_stored;");
            $stmt3->execute();
            $stmt3->close();

            $stmt = $conn->prepare("UPDATE `user` SET `password` = @storedSaltedHash WHERE `user`.`user_id` = ?");
            $stmt->bind_param('i', $userToBeModified);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyProfilePic != false) {

            $stmt = $conn->prepare("UPDATE `user` SET `profile_picture` = ? WHERE `user`.`user_id` = ?;");
            $stmt->bind_param('si', $modifyProfilePic, $userToBeModified);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyBio != false) {

            $stmt = $conn->prepare("UPDATE `user` SET `biography` = ? WHERE `user`.`user_id` = ?;");
            $stmt->bind_param('si', $modifyBio, $userToBeModified);
            $stmt->execute();
            $stmt->close();
        }

        if($modifyUserRole != false && ($modifyUserRole=='1' || $modifyUserRole=='2')) {

            $stmt = $conn->prepare("UPDATE `user` SET `user_role_id` = ? WHERE `user`.`user_id` = ?;");
            $stmt->bind_param('ii', $modifyUserRole, $userToBeModified);
            $stmt->execute();
            $stmt->close();
        }
    

    }

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['updateComment'])) {

        include ("dbconn.php");

        $userName = $_POST['userName'];
        $comment = $_POST['comment'];
        $reviewID = (int)$_POST['reviewID'];

        $stmt = $conn->prepare("UPDATE `user_album_comment` SET `user_review` = ? WHERE `user_album_comment`.`user_album_comment_id` = ?;");
        $stmt->bind_param('si', $comment, $reviewID);
        $stmt->execute();
        $stmt->close();


    }
    
    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['deleteComment'])) {

        include ("dbconn.php");

        $userName = $_POST['userName'];
        $reviewID = (int)$_POST['reviewID'];

        $stmt = $conn->prepare("DELETE FROM `user_album_comment` WHERE `user_album_comment`.`user_album_comment_id` = ?");
        $stmt->bind_param('i', $reviewID);
        $stmt->execute();
        $stmt->close();


    }

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['rejectComment'])) {

        include ("dbconn.php");

        $commentIDToReject = (int)$_POST['commentIDToReject'];
    
        $stmt = $conn->prepare("DELETE FROM `user_album_comment` WHERE `user_album_comment`.`user_album_comment_id` = ?");
        $stmt->bind_param('i', $commentIDToReject);
        $stmt->execute();
        $stmt->close();

    }

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['approveComment'])) {

        include ("dbconn.php");

        $commentIDToApprove = (int)$_POST['commentIDToApprove'];
    
        $stmt = $conn->prepare("UPDATE `user_album_comment` SET `comment_approved` = '1' WHERE `user_album_comment`.`user_album_comment_id` = ?;");
        $stmt->bind_param('i', $commentIDToApprove);
        $stmt->execute();
        $stmt->close();

    }

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_GET['deleteAccount'])) {

        include ("dbconn.php");

        $usernameForDeletion = $_POST['userName'];
    
        $stmt = $conn->prepare("SELECT * FROM `user` WHERE username = ?;");
        $stmt->bind_param('s', $usernameForDeletion);
        $stmt->execute();
        $result = $stmt->get_result();

        $userRow = $result->fetch_assoc();

        $userID = $userRow['user_id'];

        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `user_owned_album` WHERE `user_owned_album`.`user_id` = ?;");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `user_favourite_album` WHERE `user_favourite_album`.`user_id` = ?;");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `user_rating` WHERE `user_rating`.`user_id` = ?;");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `user_album_comment` WHERE `user_album_comment`.`user_id` = ?;");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `user_album_comment` WHERE `user_album_comment`.`user_id` = ?;");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `user_fav_genre` WHERE `user_fav_genre`.`user_id` = ?;");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `user_fav_genre` WHERE `user_fav_genre`.`user_id` = ?;");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("DELETE FROM `user` WHERE `user`.`user_id` = ?;");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $stmt->close();

    }

?>